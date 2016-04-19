<?php
namespace OrientDBYii2Connector\restfull;

use Yii;
use yii\rest\Action;
use OrientDBYii2Connector\restfull\ActionHelpers;

class ViewAction extends Action
{
    /**
     * Displays a model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being displayed
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $model;
    }
	
	public function findModel($id)
    {
        if ($this->findModel !== null) {
            return call_user_func($this->findModel, $id, $this);
        }

        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey();
		
		$model = null;
        if (count($keys) > 1) {
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $model = $modelClass::find()->where(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $model = $modelClass::find()->where(array_combine($keys, [$id]));
        }

        if (isset($model)) {
			if($with = ActionHelpers::getWith()) 
				$model->with($with);
            return $model->asArray()->one();
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }
}
