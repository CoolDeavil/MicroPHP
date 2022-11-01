<?php /** @noinspection PhpTypedPropertyMightBeUninitializedInspection */


namespace API\Models;


use API\Core\Utils\DateDiff;

class User
{

    private int $id;
    private string $name;
    private string $email;
    private string $pass;
    private string $avatar;
    private string $lang;
    private string $about;

    private int $created;
    private int $edited;
    private int $logged;

    private string $timeLine;
    private string $lastEdited;
    private string $lastLogged;

    public function __construct()
    {
        $args = func_get_args();
        $numArgs = func_num_args();

        if ($numArgs == 0) {
            $this->id = (int)$this->id;
            $this->name = (string)$this->name;
            $this->email = (string)$this->email;
            $this->pass = (string)$this->pass;
            $this->lang = (string)$this->lang;
            $this->about = (string)$this->about;
            $this->created = (int)$this->created;
            $this->edited = (int)$this->edited;
            $this->logged = (int)$this->logged;
            $this->avatar = (string)$this->avatar;

            $this->timeLine = (string)User::setTimeFromCreate();
            $this->lastEdited = (string)User::setTimeFromEdit();
            $this->lastLogged = (string)User::setTimeFromLastLogged();

        } else {
            call_user_func_array([$this, '__construct1'], $args);
        }
    }
    public function __construct1()
    {
        $args = func_get_args();
        $this->email = (string)$args[0];
        $this->pass = (string)$args[1];

        $this->id = 0;
        $this->created = time();
        $this->edited = time();
        $this->logged = time();
        $this->avatar = 'default_avatar.png';
        $this->about = '';
        $this->lang = 'pt';

        $this->timeLine = User::setTimeFromCreate();
        $this->lastEdited = User::setTimeFromEdit();
        $this->lastLogged = User::setTimeFromLastLogged();

    }


    public function getCreated(): int
    {
        return $this->created;
    }
    public function getLogged(): int
    {
        return $this->logged;
    }
    public function getEdited(): int
    {
        return $this->edited;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPass(): string
    {
        return $this->pass;
    }
    public function getAvatar(): string
    {
        return $this->avatar;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getAbout(): string
    {
        return $this->about;
    }
    public function getLang(): string
    {
        return $this->lang;
    }
    public function getTimeLine(): string
    {
        return $this->timeLine;
    }
    public function getLastEdited(): string
    {
        return $this->lastEdited;
    }
    public function getLastLogged(): string
    {
        return $this->lastLogged;
    }


    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;
        return $this;
    }
    public function setPass(string $pass): self
    {
        $this->pass = $pass;
        return $this;
    }
    public function setEdited(int $edited): self
    {
        $this->edited = $edited;
        return $this;
    }
    public function setLogged(int $logged): self
    {
        $this->logged = $logged;
        return $this;
    }
    public function setCreated(int $created): self
    {
        $this->created = $created;
        return $this;
    }
    public function setLang(string $lang): self
    {
        $this->lang = $lang;
        return $this;
    }
    public function setAbout(string $about): self
    {
        $this->about = $about;
        return $this;
    }


    private function setTimeFromCreate(): string
    {
       $this->timeLine = DateDiff::diff(
            $this->getCreated(),
            time()
        );
       return $this->timeLine;

    }
    private function setTimeFromEdit(): string
    {
        $this->lastEdited = DateDiff::diff(
            $this->getEdited(),
            time()
        );
        return $this->lastEdited;
    }
    public function setTimeFromLastLogged() :string {
        $this->lastLogged = DateDiff::diff(
            $this->getLogged(),
            time()
        );
        return $this->lastLogged;

    }





}