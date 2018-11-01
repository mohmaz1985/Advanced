<?php

namespace backend\modules\auth\models;

use dosamigos\arrayquery\ArrayQuery;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\rbac\Item;
use backend\modules\auth\models\AuthItem;

/**
 * AuthItemSearch represents the model behind the search form about `backend\modules\auth\models\AuthItem`.
 */
class AuthItemSearch extends Model
{
    /**
     * @var string auth item name
     */
    public $name;

    /**
     * @var int auth item type
     */
    public $type;

    /**
     * @var string auth item description
     */
    public $description;

    /**
     * @var string auth item rule name
     */
    public $ruleName;

    /**
     * @var int the default page size
     */
    public $pageSize = 25;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'ruleName'], 'safe'],
            [['type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        
        $authManager = Yii::$app->getAuthManager();

        if($this->type == Item::TYPE_ROLE){
            $items = $authManager->getRoles();
        }else{
            $items = $authManager->getPermissions();
        }
       
              
        $query = new ArrayQuery($items);
        // add conditions that should always apply here

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->find(),
        ]);

        $this->load($params);

       
        if ($this->validate()) {
            $query->addCondition('name', $this->name ? "~{$this->name}" : null)
                ->addCondition('ruleName', $this->ruleName ? "~{$this->ruleName}" : null)
                ->addCondition('description', $this->description ? "~{$this->description}" : null);
        }

        return $dataProvider;
    }
}
