<?php
namespace API\Repository;


use API\Core\Database\Database;
use API\Core\Database\Model;
use API\Core\Session\Session;
use API\Core\Utils\AppCrypt;
use API\Core\Utils\Logger;
use API\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthorRepository extends Model
{
    protected \PDO $conn;

    public function __construct(Database $db)  {
            parent::__construct($db);
            $this->conn=$db->getConnection();

            AuthorRepository::BuildTable();
            $query = "SELECT * FROM Users;";
            $stmt = $this->conn->query($query);
            if (!$stmt->fetchAll(\PDO::FETCH_ASSOC)) {
                AuthorRepository::seed();
            }


    }
    private function seed()
    {
        $newUserID = $this->store((new User('john@mail.com','John0000'))
            ->setName('John Doe')
            ->setCreated(time())
            ->setLogged(time())
            ->setEdited(time())
            ->setPass(AppCrypt::hashFactory('John0000'))
            ->setAbout('Lorem Ipsum Dolor')
            ->setAvatar('default_avatar.png')
            ->setLang('pt'));
    }

    public function buildTable()
    {
        $sqlData = match (DB_TYPE) {
            'memory', 'sqlite'=> file_get_contents(PATH_BUILD_TABLES . "table_users_sqlite.sql"),
            'mysql' =>  file_get_contents(PATH_BUILD_TABLES . "table_users_mysql.sql"),
            default => null,
        };
        $conn = $this->db->getConnection();
        $conn->exec($sqlData);
    }

    public function get(int $id): User
    {
        $query = "SELECT * FROM Users WHERE Users.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetchObject("\\API\\Models\\User");
    }

    public function update(User $user): void
    {
        $query = "UPDATE Users SET
                        created = :created,
                        logged = :logged,
                        edited = :edited,
                        email = :email,
                        name = :name,
                        pass = :pass,
                        avatar = :avatar,
                        lang = :lang,
                        about = :about
                WHERE id= :id;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'created' => $user->getCreated(),
            'logged'  => $user->getLogged(),
            'edited'  => $user->getEdited(),
            'email'  => $user->getEmail(),
            'name'  => $user->getName(),
            'pass'  => $user->getPass(),
            'avatar'  => $user->getAvatar(),
            'lang' => $user->getLang(),
            'about' => $user->getAbout(),
            'id' => $user->getId()
        ]);

    }

    public function store(User $user): int
    {
        $query = "INSERT INTO Users (
                    created,
                    logged,
                    edited,
                    email,
                    name, 
                    pass, 
                    avatar,
                    lang,
                    about
                    ) VALUES(
                    :created, 
                    :logged, 
                    :edited, 
                    :mail, 
                    :name, 
                    :pass, 
                    :avatar,
                    :lang,
                    :about        
                );";
        $conn = $this->db->getConnection();
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute([
                'created'  => $user->getCreated(),
                'logged'  => $user->getLogged(),
                'edited'  => $user->getEdited(),
                'mail' => $user->getEmail(),
                'name'  => $user->getName(),
                'pass' => $user->getPass(),
                'avatar' => $user->getAvatar(),
                'lang' => $user->getLang(),
                'about' => $user->getAbout(),
            ]);
        } catch (\PDOException $e) {
            die("Failed to Insert New USER");
//            return false;
        }
        return (int)$conn->lastInsertId();
    }

    public function destroy(User $user): void
    {
        $query = "DELETE FROM Users WHERE id=:id;";
        $stmt = $this->conn->prepare($query);

    }

    public function validate(User $user) : int
    {
        $stmt = $this->conn->prepare('SELECT * FROM Users WHERE email = :email AND pass = :pass;');
        $stmt->execute([
            'email' =>  $user->getEmail(),
            'pass' =>  $user->getPass(),
        ]);
        /**@var $userData AppUser */
        $userData = $stmt->fetchObject(User::class);
        if ($userData) {
            return $userData->getId();
        }
        return 0;
    }

    public function collection(): array
    {
        $stmt = $this->conn->prepare('SELECT * FROM Users;');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS,User::class);

    }
}