<?php
namespace OrientDBYii2Connector\restfull;

use Yii;
use yii\rest\Action;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use OrientDBYii2Connector\restfull\ActionHelpers;

class IndexAction extends Action
{
    public $prepareDataProvider;
	
    /**
     * @return ActiveDataProvider
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        return $this->prepareDataProvider();
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider()
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

		/* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;
		$query = $modelClass::find();
		
		$filters = ActionHelpers::getFilter();
		
		foreach($filters as $filter) {
			$query->andFilterWhere($filter);
		}
		
		if($with = ActionHelpers::getWith()) {
            $query->with($with);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => ActionHelpers::getLimit()
            ],
            'sort'=> ActionHelpers::getSort()
            // 'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);
    }
}
