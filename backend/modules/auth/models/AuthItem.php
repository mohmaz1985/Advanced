<?php

namespace backend\modules\auth\models;

use Yii;
use yii\base\Model;
use yii\rbac\Item;
use yii\helpers\Json;



/**
 * Class AuthItemModel
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $ruleName
 * @property string $data
 * @property Item $item
 */

class AuthItem extends Model
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
     * @var string biz rule name
     */
    public $ruleName;

    /**
     * @var null|string additional data
     */
    public $data;

    /**
     * @var \yii\rbac\ManagerInterface
     */
    protected $manager;

    /**
     * @var Item
     */
    private $_item;

     public function __construct($item = null, $config = [])
    {
        $this->_item = $item;
        $this->manager = Yii::$app->authManager;

        if ($item !== null) {
            $this->name = $item->name;
            $this->type = $item->type;
            $this->description = $item->description;
            $this->ruleName = $item->ruleName;
            $this->data = $item->data === null ? null : Json::encode($item->data);
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        ///, 'ruleName'
        return [
            [['name', 'description', 'data'], 'trim'],
            [['name', 'type'], 'required'],
            ['name', 'validateName', 'when' => function () {
                return $this->getIsNewRecord() || ($this->_item->name != $this->name);
            }],
            [['type'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'ruleName'], 'string', 'max' => 64],

        ];
    }

    /**
     * Validate item name
     */
    public function validateName()
    {   
        $value = $this->name;

        if ($this->manager->getRole($value) !== null || $this->manager->getPermission($value) !== null) {
            $message = '{attribute} "{value}" has already been taken.';

            $params = [
                'attribute' => $this->getAttributeLabel('name'),
                'value' => $value,
            ];
            $this->addError('name', Yii::$app->getI18n()->format($message, $params, Yii::$app->language));
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    /**
     * Check if is new record.
     *
     * @return bool
     */
    public function getIsNewRecord(): bool
    {
        return $this->_item === null;
    }

     /**
     * Save role to [[\yii\rbac\authManager]]
     *
     * @return bool
     */
    public function save(): bool
    {
       
        if ($this->validate()) {

            if ($this->_item === null) {
                if ($this->type == Item::TYPE_ROLE) {
                    $this->_item = $this->manager->createRole($this->name);
                } else {
                    $this->_item = $this->manager->createPermission($this->name);
                }
                $isNew = true;
                $oldName = false;
            } else {
                $isNew = false;
                $oldName = $this->_item->name;
            }

            $this->_item->name = $this->name;
            $this->_item->description = $this->description;
            $this->_item->ruleName = $this->ruleName;
            $this->_item->data = Json::decode($this->data);

            if ($isNew) {
                $this->manager->add($this->_item);
            } else {
                $this->manager->update($oldName, $this->_item);
            }

            return true;
        }
        return false;
    }

    /**
     * @return null|Item
     */
    public function getItem()
    {
        return $this->_item;
    }
}
