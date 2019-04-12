<?php
namespace App\Controller\V1;

use App\Controller\AppController;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use App\Auth\Md5PasswordHasher;

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
        $this->Auth->allow(['add', 'activate']);
    }

    public function isAuthorized($user)
    {
        return $user['active'];
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
            if ($user_saved = $this->Users->save($user)) {

                $hash = Md5PasswordHasher::hash($user_saved->id);

                // $email = new Email('default');
                // $response = $email->from(['adryssonlc@gmail.com' => 'My Test'])
                //     ->to($this->request->getData('email'))
                //     ->subject('Confirmação de e-mail')
                //     ->send('Seu cadastro foi realizado com sucesso. Para ativar sua conta, acesse o link: ' . env('APP_URL', 'https://google.com.br') . '/#/ativacao/' . $hash);
                $this->set([
                    'url' => env('APP_URL', 'https://google.com.br') . '/#/ativacao/' . $hash,
                    'message' => __('The user has been saved. Make an activation of your account through the link'),
                    '_serialize' => ['url', 'message']
                ]);
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
                    'user' => $user,
                    '_serialize' => ['token', 'user'],
                ]);
            } else {
                return $this->response->withStatus(422)->withStringBody(__('Username or password is incorrect'));
            }
        }
    }
    
    public function activate()
    {
        if ($this->request->is('post')) {
            $user = $this->Users->find('all', [
                'conditions' => [
                    'MD5(CONCAT("' . Security::getSalt() . '",Users.id))' => $this->request->getData('hash'),
                    'active' => false,
                ],
            ])->first();

            if ($user) {

                $user->active = true;

                if ($user_saved = $this->Users->save($user)) {
                    $token = JWT::encode([
                        'sub' => $user['id'],
                        'exp' => time() + 3600 * 24 * 7,
                    ], Security::getSalt());

                    $this->set([
                        'token' => $token,
                        'user' => $user_saved,
                        '_serialize' => ['token', 'user'],
                    ]);
                } else {
                    $this->response = $this->response->withStatus(422);
                    $this->set([
                        'errors' => $user->errors(),
                        '_serialize' => 'errors'
                    ]);
                }
            } else {
                return $this->response->withStatus(400)->withStringBody(__('This link is not available'));
            }
        }
    }
}
