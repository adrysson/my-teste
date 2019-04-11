<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false)
            ->add('name', 'complete', [
                // Exige que o usuário escreva seu nome completo
                'rule' => function ($value) {
                    return count(explode(' ', trim(preg_replace('/\s+/', ' ',$value)))) > 1;
                },
                'message' => __('Enter your full name'),
            ]);

        $validator
            ->scalar('username')
            ->maxLength('username', 50)
            ->requirePresence('username', 'create')
            ->allowEmptyString('username', false);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->allowEmptyString('email', false);

        $validator
            ->scalar('password')
            ->minLength('password', 6)
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->allowEmptyString('password', false)
            ->add('password', 'valid_password', [
                // Exige que o usuário escreva sua senha contendo pelo menos uma letra minúscula, uma letra maiúscula e números
                'rule' => function ($value) {
                    $count_uppercase = preg_match_all('#([A-Z])#',$value);
                    $count_lowercase = preg_match_all('#([a-z])#',$value);
                    $count_numbers = preg_match_all('#([0-9])#',$value);
                    return $count_uppercase > 0 && $count_lowercase > 0 && $count_numbers > 0;
                },
                'message' => __('The password must contain at least one lowercase letter, one uppercase letter, and numbers'),
            ]);

        $validator
            ->boolean('active')
            ->requirePresence('active', 'update')
            ->allowEmptyString('active', false);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['hash']));

        return $rules;
    }
}
