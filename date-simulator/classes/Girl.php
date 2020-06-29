<?php

class Girl
{
    /* ------------------------------- PROPRIETES DE L'OBJET ------------------------------- */

    protected $id;
    protected $name;
    protected $age;
    protected $tempted;
    protected $dressed;
    protected $actionsNumber;

    /* Construction de l'objet qui exécute la méthode "hydrate" avec en argument les données données en paramètre */
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    /* Attribue des valeurs aux propriétés d'après les données passées en paramètre pour chaque Setter existant */
    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' .ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }


    /* ------------------------------- GETTERS AND SETTERS ------------------------------- */

    /* Renvoie l'id de l'objet */
    public function getId()
    {
        return $this->id;
    }

    /* Attribue une valeur (nombre) à l'id de l'objet */
    public function setId(int $id)
    {
        $this->id = $id;
    }


    /* Renvoie le nom de l'objet */
    public function getName()
    {
        return ucfirst($this->name);
    }

    /* Attribue une valeur (string) au nom de l'objet */
    public function setName(string $name)
    {
        $this->name = $name;
    }


    /* Renvoie l'âge de l'objet */
    public function getAge()
    {
        return $this->age;
    }

    /* Attribue une valeur (number) à l'âge de l'objet */
    public function setAge(int $age)
    {
        $this->age = $age;
    }


    /* Renvoie le niveau de tentation de l'objet */
    public function getTempted()
    {
        return $this->tempted;
    }

    /* Attribue une valeur (nombre) au niveau de tentation de l'objet */
    public function setTempted(int $tempted)
    {
        $this->tempted = $tempted;
    }
    

    /* Renvoie le statut "habillée" de l'objet */
    public function getDressed()
    {
        return $this->dressed;
    }

    /* Attribue une valeur (number) au statut "habillée" de l'objet */
    public function setDressed(int $dressed)
    {
        $this->dressed = $dressed;
    }


    /* Renvoie le nombre d'actions effectuées sur l'objet */
    public function getActionsNumber()
    {
        return $this->actionsNumber;
    }

    /* Attribue une valeur (number) au nombre d'actions effectuées sur l'objet */
    public function setActionsNumber(int $actionsNumber)
    {
        $this->actionsNumber = $actionsNumber;
    }



    /* ------------------------------- METHODS ------------------------------- */

    /* Attribue à l'objet un nouveau nom d'après le surnom donné en paramètre, et augmente son niveau de tentation de 1 */
    public function nickname(string $nickname)
    {
        $this->name = $nickname;
        $this->tempted += 1;

        $this->actionsNumber += 1;
        $this->checkWin();

        return ($this->name.' adore son nouveau surnom et se sent plus proche de toi.');
    }

    /* Augmente le niveau de tentation de 1 */
    public function compliment($compliment)
    {
        $this->tempted += 1;

        $this->actionsNumber += 1;
        $this->checkWin();

        return ($this->name.' est ravie d\'entendre que tu la trouves '.$compliment.'.');
    }

    /* Augmente le niveau de tentation de 1 */
    public function offerGift($gift)
    {
        $this->tempted += 1;

        $this->actionsNumber += 1;
        $this->checkWin();

        return ($this->name.' est ravie de recevoir '.$gift.'.');
    }

    /* Si son âge est supérieur ou égal à 30 ans, échec et diminution du niveau de tentation, sinon succès et augmentation du niveau de tentation */
    public function mentionHerAge()
    {
        if ($this->age >= 30) {
            $this->tempted -= 1;
            $this->actionsNumber += 1;
            $this->checkWin();
            return ($this->name.' n\'apprécie pas que tu évoques son âge, elle est vexée !');            
        } else {
            $this->tempted += 2;
            $this->actionsNumber += 1;
            $this->checkWin();
            return ($this->name.' est flattée que tu te rappelles son âge, un bon point pour toi !');
        }
    }

    /* Si le niveau de tentation est inférieur à 3, échec et diminution du niveau de tentation, sinon succès et augmentation du niveau de tentation */
    public function kiss()
    {
        if ($this->tempted < 3) {
            $this->tempted -= 2;
            $this->actionsNumber += 1;
            $this->checkWin();
            return ($this->name.' n\'est pas encore sous le charme... Ta tentative la contrarie, c\'est un échec !');
        } else {
            $this->tempted += 3;
            $this->actionsNumber += 1;
            $this->checkWin();
            return ($this->name.' est conquise... Ton initiative lui plaît, c\'est un succès !');
        }
    }

     /* Augmente le niveau de tentation de 1 */
     public function checkWin()
     {
        if ($this->tempted >= 10) {
            $this->dressed = 0;
            return ($this->name.' est totalement sous le charme... Ses vêtements se consument automatiquement. Victoire !');
        }
     }
}

?>