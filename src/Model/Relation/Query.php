<?php
namespace Imi\Model\Relation;

use Imi\Util\Imi;
use Imi\Util\Text;
use Imi\Util\ArrayList;
use Imi\Bean\BeanFactory;
use Imi\Model\ModelManager;
use Imi\Model\Parser\RelationParser;
use Imi\Model\Relation\Struct\OneToOne;
use Imi\Model\Relation\Struct\OneToMany;


abstract class Query
{
	/**
	 * 初始化
	 *
	 * @param \Imi\Model\Model $model
	 * @param string $propertyName
	 * @param \Imi\Bean\Annotation\Base $annotation
	 * @return void
	 */
	public static function init($model, $propertyName, $annotation)
	{
		$relationParser = RelationParser::getInstance();
		$className = BeanFactory::getObjectClass($model);

		$autoSelect = $relationParser->getPropertyAnnotation($className, $propertyName, 'AutoSelect');
		if($autoSelect && !$autoSelect->status)
		{
			return;
		}

		if($annotation instanceof \Imi\Model\Annotation\Relation\OneToOne)
		{
			static::initByOneToOne($model, $propertyName, $annotation);
		}
		else if($annotation instanceof \Imi\Model\Annotation\Relation\OneToMany)
		{
			static::initByOneToMany($model, $propertyName, $annotation);
		}
		else if($annotation instanceof \Imi\Model\Annotation\Relation\ManyToMany)
		{
			static::initByManyToMany($model, $propertyName, $annotation);
		}
	}

	/**
	 * 初始化一对一关系
	 *
	 * @param \Imi\Model\Model $model
	 * @param string $propertyName
	 * @param \Imi\Model\Annotation\Relation\OneToOne $annotation
	 * @return void
	 */
	public static function initByOneToOne($model, $propertyName, $annotation)
	{
		$className = BeanFactory::getObjectClass($model);

		if(class_exists($annotation->model))
		{
			$modelClass = $annotation->model;
		}
		else
		{
			$modelClass = Imi::getClassNamespace($className) . '\\' . $annotation->model;
		}

		$struct = new OneToOne($className, $propertyName, $annotation);
		$leftField = $struct->getLeftField();
		$rightField = $struct->getRightField();

		if(null === $model->$leftField)
		{
			$rightModel = $modelClass::newInstance();
		}
		else
		{
			$rightModel = $modelClass::query()->where($rightField, '=', $model->$leftField)->select()->get();
			if(null === $rightModel)
			{
				$rightModel = $modelClass::newInstance();
			}
		}

		$model->$propertyName = $rightModel;
	}

	/**
	 * 初始化一对多关系
	 *
	 * @param \Imi\Model\Model $model
	 * @param string $propertyName
	 * @param \Imi\Model\Annotation\Relation\OneToMany $annotation
	 * @return void
	 */
	public static function initByOneToMany($model, $propertyName, $annotation)
	{
		$className = BeanFactory::getObjectClass($model);

		if(class_exists($annotation->model))
		{
			$modelClass = $annotation->model;
		}
		else
		{
			$modelClass = Imi::getClassNamespace($className) . '\\' . $annotation->model;
		}

		$struct = new OneToMany($className, $propertyName, $annotation);
		$leftField = $struct->getLeftField();
		$rightField = $struct->getRightField();

		$model->$propertyName = new ArrayList($modelClass);
		if(null !== $model->$leftField)
		{
			$list = $modelClass::query()->where($rightField, '=', $model->$leftField)->select()->getArray();
			if(null !== $list)
			{
				$model->$propertyName->append(...$list);
			}
		}

	}

}