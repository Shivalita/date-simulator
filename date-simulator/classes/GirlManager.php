<?php

class GirlManager
{
    /* ------------------------------- CONSTRUCTION DU MANAGER ------------------------------- */
    private $database;

    /* Attribue la connexion à la base de données à la propriété "database" du manager */
    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    /* ---------------------------------------- CRUD ---------------------------------------- */

    /* -------------------- CREATE -------------------- */
    public function createGirl(Girl $girl)
    {
        /* Se prepare à insérer des données dans les colonnes "name" et "age" */
        $addGirlQuery = $this->database->prepare(
            'INSERT INTO girls(name, age)
             VALUES(:name, :age)'
        );

        /* Attribue une valeur à ces données à partir des propriétés de l'objet $girl donné en paramètre */
        $addGirlQuery->bindValue(
            ':name', $girl->getName()
        );
        $addGirlQuery->bindValue(
            ':age', $girl->getAge()
        );

        $addGirlQuery->execute();

        /* Attribue l'id (qui est le dernier enregistré) et des valeurs par défaut aux propriétés "tempted" et "dressed" */
        $girl->hydrate([
            'id' => $this->database->lastInsertId(),
            'actionsNumber' => 0,
            'tempted' => 0,
            'dressed' => true
        ]);
    }

    /* -------------------- GET -------------------- */
    public function getGirl($request)
    {
        /* Si la requête est un nombre, recherche par son id et stocke les données dans la variable $girlData */
        if (is_int($request)) {
            $getGirlQuery = $this->database->prepare(
                'SELECT * FROM girls WHERE id = ?'
            );
            $getGirlQuery->execute([$request]);
            $girlData = $getGirlQuery->fetch(PDO::FETCH_ASSOC);
            
        /* Sinon recherche par son nom et stocke les données dans la variable $girlData */
        } else {
            $getGirlQuery = $this->database->prepare(
                'SELECT * FROM girls WHERE name = ?'
            );
            $getGirlQuery->execute([$request]);
            $girlData = $getGirlQuery->fetch(PDO::FETCH_ASSOC);
            
        }

        /* Retourne une instance de l'objet Girl, avec pour propriétés les données stockées dans $girlData */
        return new Girl($girlData);
    }

    /* -------------------- UPDATE -------------------- */
    public function updateGirl(Girl $girl)
    {
        /* Se prépare à mettre à jour les données d'un élément de la table "girls", en le recherchant par son id */
        $updateGirlQuery = $this->database->prepare(
            'UPDATE girls
             SET name = ?, age = ?, tempted = ?, dressed = ?, actions_number = ?
             WHERE id = ?'
        );

        /* Met à jour les données d'après les propriétés de l'objet $girl donné en paramètre (l'id déterminant l'élément à modifier) */
        $updateGirlQuery->execute([
            $girl->getName(),
            $girl->getAge(),
            $girl->getTempted(),
            $girl->getDressed(),
            $girl->getActionsNumber(),
            $girl->getId()
        ]);
    }

    /* -------------------- DELETE -------------------- */
    public function delete(Girl $girl)
    {
        /* Se prepare à supprimer toutes les données d'un élément dans la table "girls" */
        $deleteGirlQuery = $this->database->prepare(
            'DELETE * FROM girls WHERE id = ?'
        );

        /* Exécute la supression sur l'élément dont l'id correspond à celui de l'objet donné en paramètre */
        $deleteGirlQuery->execute([$girl->getId()]);
    }


    /* ---------------------------------------- OTHER METHODS ---------------------------------------- */

    public function girlExists($girl)
    {
        if (is_int($girl)) {
            return (bool) $this->database->query(
                'SELECT COUNT(*) FROM girls WHERE id = '
                .$girl)->fetchColumn();
        }

        $checkGirlExists = $this->database->prepare(
            'SELECT COUNT(*) FROM girls WHERE name = :name'
        );
        $checkGirlExists->execute([':name' => $girl]);
        
        return (bool) $checkGirlExists->fetchColumn();           
    }
    
}

?>