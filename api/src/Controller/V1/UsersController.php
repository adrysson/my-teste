<?php
namespace App\Controller\V1;

use App\Controller\AppController;
use Firebase\JWT\JWT;
use Cake\Utility\Security;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['add']);
    }
    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set([
            'user' => $user,
            '_serialize' => ['user'],
        ]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                return $this->response->withStatus(200)->withStringBody(__('The user has been saved.'));
            } else {
                $this->response = $this->response->withStatus(422);
                $this->set([
                    'errors' => $user->errors(),
                    '_serialize' => 'errors'
                ]);
            }
        }
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                $token = JWT::encode([
                    'sub' => $user['id'],
                    'exp' => time() + 3600 * 24 * 7,
                ], Security::getSalt());

                $this->set([
                    'token' => $token,
                    '_serialize' => ['token'],
                ]);
            } else {
                return $this->response->withStatus(422)->withStringBody(__('Username or password is incorrect'));
            }
        }
    }
}
