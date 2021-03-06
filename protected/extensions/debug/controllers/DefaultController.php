<?php

/**
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 * @package Yii2Debug
 * @since 1.1.13
 */
class DefaultController extends CController
{
	public $layout = 'main';
	public $summary;

	/**
	 * @return Yii2Debug
	 */
	public function getComponent()
	{
		return $this->getModule()->component;
	}

	/**
	 * Общий список логов
	 */
	public function actionIndex()
	{
		$this->render('index', array(
			'manifest' => $this->getManifest(),
		));
	}

	/**
	 * Страница для просмотра отладочной информации
	 * @param null $tag сохраненного лога
	 * @param null $panel id страницы
	 */
	public function actionView($tag = null, $panel = null)
	{
		if ($tag === null) {
			$tags = array_keys($this->getManifest());
			$tag = reset($tags);
		}
		$this->loadData($tag);
		if (isset($this->component->panels[$panel])) {
			$activePanel = $this->getComponent()->panels[$panel];
		} else {
			$activePanel = $this->getComponent()->panels['request'];
		}
		$this->render('view', array(
			'tag' => $tag,
			'summary' => $this->summary,
			'manifest' => $this->getManifest(),
			'panels' => $this->getComponent()->panels,
			'activePanel' => $activePanel,
		));
	}

	/**
	 * @param string $tag
	 * @param int $num
	 * @param string $connection
	 * @throws CHttpException
	 * @throws Exception
	 */
	public function actionExplain($tag, $num, $connection)
	{
		$this->loadData($tag);

		$dbPanel = $this->getComponent()->panels['db'];
		if (!($dbPanel instanceof Yii2DbPanel)) {
			throw new Exception('Yii2DbPanel not found');
		}
		if (!$dbPanel->canExplain) {
			throw new CHttpException(403, 'Forbidden');
		}
		$message = $dbPanel->messageByNum($num);
		if ($message === null) {
			throw new Exception("Not found query by number $num");
		}
		$query = $dbPanel->formatSql($message, true);
		/* @var CDbConnection $db */
		$db = Yii::app()->getComponent($connection);

		if (!Yii::app()->request->isAjaxRequest) {
			$this->render('explain', array(
				'tag' => $tag,
				'summary' => $this->summary,
				'manifest' => $this->getManifest(),
				'panels' => $this->getComponent()->panels,
				'dbPanel' => $dbPanel,
				'connection' => $db,
				'procedure' => Yii2DbPanel::getExplainQuery($query, $db->driverName),
				'explainRows' => Yii2DbPanel::explain($query, $db),
			));
		} else {
			$this->renderPartial('_explain', array(
				'connection' => $db,
				'explainRows' => Yii2DbPanel::explain($query, $db),
			));
		}
	}

	/**
	 * Генерирует код дебаг-панели по ajax-запросу
	 * @param $tag
	 */
	public function actionToolbar($tag)
	{
		$this->loadData($tag);
		$this->renderPartial('toolbar', array(
			'tag' => $tag,
			'panels' => $this->getComponent()->panels,
		));
	}

	public function actionPhpinfo()
	{
		phpinfo();
	}

	private $_manifest;

	protected function getManifest()
	{
		if ($this->_manifest === null) {
			$path = $this->getComponent()->logPath;
			$indexFile = "$path/index.json";
			if (is_file($indexFile)) {
				$this->_manifest = array_reverse(json_decode(file_get_contents($indexFile), true), true);
			} else {
				$this->_manifest = array();
			}
		}
		return $this->_manifest;
	}

	protected function loadData($tag)
	{
		$manifest = $this->getManifest();
		if (isset($manifest[$tag])) {
			$path = $this->getComponent()->logPath;
			$dataFile = "$path/$tag.json";
			$data = json_decode(file_get_contents($dataFile), true);
			foreach ($this->getComponent()->panels as $id => $panel) {
				if (isset($data[$id])) {
					$panel->tag = $tag;
					$panel->load($data[$id]);
				} else {
					// remove the panel since it has not received any data
					unset($this->getComponent()->panels[$id]);
				}
			}
			$this->summary = $data['summary'];
		} else {
			throw new CHttpException(404, "Unable to find debug data tagged with '$tag'.");
		}
	}

	public function actionConfig()
	{
		if (!$this->getComponent()->showConfig) {
			throw new CHttpException(403, 'Forbidden');
		}
		$components = array();
		foreach (Yii::app()->getComponents(false) as $id => $config) {
			try {
				$components[$id] = Yii::app()->getComponent($id);
			} catch (Exception $e) {
				assert(is_array($config));
				$components[$id] = array_merge($config, array(
					'_error_' => $e->getMessage(),
				));
			}
		}
		ksort($components);
		$modules = Yii::app()->modules;
		ksort($modules);
		$data = $this->hideConfigData(
			array(
				'app' => $this->prepareData(get_object_vars(Yii::app())),
				'components' => $this->prepareData($components),
				'modules' => $this->prepareData($modules),
				'params' => $this->prepareData(Yii::app()->params),
			),
			$this->getComponent()->hiddenConfigOptions
		);
		$this->render('config', $data);
	}

	private function prepareData($data)
	{
		$result = array();
		foreach ($data as $key => $value) {
			if (is_object($value)) {
				$value = array_merge(array(
					'class' => get_class($value)
				), get_object_vars($value));
			}
			$result[$key] = $value;
		}
		return $result;
	}

	/**
	 * @param array $config
	 * @param array $options
	 * @return array
	 */
	private function hideConfigData($config, $options)
	{
		foreach ($options as $option) {
			$item = &$config;
			foreach (explode('/', $option) as $key) {
				if (is_array($item) && isset($item[$key])) {
					$item = &$item[$key];
				} else {
					unset($item);
					break;
				}
			}
			if (isset($item)) {
				$item = '**********';
				unset($item);
			}
		}
		return $config;
	}
}
