<?php

//use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTeste extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $dados = [
            'name'                   => 'Nome 01',
            'email'                  => 'email@exemplo.com',
            'password'               => '123',
            'password_confirmation'  => '123'
        ];

        $this->post('/api/user', $dados);
        $this->assertResponseOk();

        

        $resposta = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('name', $resposta);
        $this->assertArrayHasKey('email', $resposta);
        $this->assertArrayHasKey('id', $resposta);
    }

    public function testViewUser()
    {
        $user = \App\User::first();

        $this->get('/api/user/'.$user->id);
        $this->assertResponseOk();

        $resposta = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('name', $resposta);
        $this->assertArrayHasKey('email', $resposta);
        $this->assertArrayHasKey('id', $resposta);
    }

    public function testUpdateWithPasswordUser()
    {
        $user = \App\User::first();

        $dados = [
            'name'                   => 'Nome 02'.date('Ymdis').' '.rand(1,100),
            'email'                  => 'email4@exemplo.com',
            'password'               => '123',
            'password_confirmation'  => '123'
        ];

        $this->put('/api/user/'.$user->id, $dados);


        $this->assertResponseOk();

        $resposta = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('name', $resposta);
        $this->assertArrayHasKey('email', $resposta);
        $this->assertArrayHasKey('id', $resposta);

        $this->seeInDatabase('users', [
            'name'  => $dados['name'],
            'email' => $dados['email']
        ]);

        $this->notSeeInDatabase('users', [
            'name'  => $user->name,
            'email' => $user->email
        ]);
    }

    public function testUpdateWithoutPasswordUser()
    {
        $user = \App\User::first();

        $dados = [
            'name'                   => 'Nome 02'.date('Ymdis').' '.rand(1,100),
            'email'                  => 'email4@exemplo.com'
        ];

        $this->put('/api/user/'.$user->id, $dados);

        $this->assertResponseOk();

        $resposta = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('name', $resposta);
        $this->assertArrayHasKey('email', $resposta);
        $this->assertArrayHasKey('id', $resposta);

        $this->seeInDatabase('users', [
            'name'  => $dados['name'],
            'email' => $dados['email']
        ]);

        $this->notSeeInDatabase('users', [
            'name'  => $user->name,
            'email' => $user->email
        ]);
    }

    public function testAllUser()
    {
        $this->get('/api/users');
        $this->assertResponseOk();
        $this->seeJsonStructure([
            '*' => [
                'id',
                'name',
                'email'
            ]
        ]);
    }

    public function testDeleteUser()
    {
        $user = \App\User::first();

        $this->delete('/api/user/'.$user->id);
        $this->assertResponseOk();
        $this->assertEquals('Removido com sucesso.', $this->response->content());
    }
}
