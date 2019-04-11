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

            $hash = Md5PasswordHasher::hash(microtime());

            $user = $this->Users->patchEntity($user, $this->request->withData('hash', $hash)->getData());
            if ($this->Users->save($user)) {

                $email = new Email('sendgrid');
                $email->from(['adryssonlc@gmail.com' => 'My Test'])
                    ->to($this->request->getData('email'))
                    ->subject('Confirmação de e-mail')
                    ->send('Seu cadastro foi realizado com sucesso. Para ativar sua conta, acesse o link: ' . Router::url(['action' => 'activate', $hash], true));

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
                    'user' => $user,
                    '_serialize' => ['token', 'user'],
                ]);
            } else {
                return $this->response->withStatus(422)->withStringBody(__('Username or password is incorrect'));
            }
        }
    }
    
    public function activate($hash)
    {
        $user = $this->Users->find('all', [
            'conditions' => [
                'hash' => $hash,
                'active' => false,
            ],
        ])->first();

        if ($user) {
            $user->active = true;
            if ($this->Users->save($user)) {
                $this->redirect(env('APP_URL', 'https://google.com.br'));
            }
        }
        return $this->response->withStatus(400)->withStringBody(__('This link is not available'));
    }
}
