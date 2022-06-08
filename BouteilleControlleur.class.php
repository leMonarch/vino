<?php
/**
 * Class BouteilleControleur
 * Controleur de la ressource Bouteille
 * 
 * @author Equipe de 4
 * @version 1.1
 * @update 2019-11-11
 * @license MIT
 */

  
class BouteilleControlleur 
{
	private $retour = array('data'=>array());

	/**
	 * Méthode qui gère les action en GET
     * @access public
	 * @param Requete $requete
	 * @return Mixed Données retournées
	 */
	public function getAction(Requete $requete)
	{
		if(isset($requete->url_elements[0])) 
		{
            if(is_numeric($requete->url_elements[0])) 
            {
				$id_bouteille = $requete->url_elements[0];
                switch($requete->url_elements[1]) 
					{
						case 'quantite':
							$this->retour["data"] = $requete->url_elements;
							$this->ajouterQuantiteBouteille($id_bouteille);
							break;
						default:
							$this->retour['erreur'] = $this->erreur(400);
							unset($this->retour['data']);
							break;
					}
            } 
            else
            {
                switch($requete->url_elements[0]) 
                    {
                        case 'bouteilles':
                            $this->retour["data"] = $this->getBouteillesInserer();
                            break;
                        default:
                            $this->retour['erreur'] = $this->erreur(400);
                            unset($this->retour['data']);
                            break;
                    }
            }
		} 
		else 
		{
			$this->retour["data"] = $this->getBouteilles();
		}
        return $this->retour;		
	}
	

	/**
	 * Méthode qui gère les action en POST
     * @access public
	 * @param Requete $requete
	 * @return Mixed Données retournées
	 */
	public function postAction(Requete $requete)	// Modification
	{
        if(isset($requete->url_elements[0]) && is_numeric($requete->url_elements[0]))	// l'id de la bouteille 
        {
            //$id = (int)$requete->url_elements[0];
            $this->retour["data"] = $this->modifBouteille($requete->parametres);
        }
        else{
            $this->retour['erreur'] = $this->erreur(400);
            unset($this->retour['data']);
        }
        return $this->retour;
	}


	/**
	 * Méthode qui gère les action en PUT
     * @access public
	 * @param Requete $requete
	 * @return Mixed Données retournées
	 */
	public function putAction(Requete $requete)		//ajout ou modification
	{
        if(isset($requete->url_elements[0]) && is_numeric($requete->url_elements[0]))	// Normalement l'id de la bouteille 
        {
            $id_bouteille = (int)$requete->url_elements[0];
            
            if(isset($requete->url_elements[1])) 
            {
                switch($requete->url_elements[1]) 
                {
                    case 'quantite':
                        $this->retour["data"] = $this->ajouterQuantiteBouteille($id_bouteille);
                        break;
                    default:
                        $this->retour['erreur'] = $this->erreur(400);
                        unset($this->retour['data']);
                        break;
                }
            } 
            else
            {
                $this->retour['erreur'] = $this->erreur(400);
                unset($this->retour['data']);
            }
        } 
        else 
        {
            $this->retour["data"] = $this->ajouterUneBouteille($requete->parametres);
        }
		return $this->retour;
	}


	/**
	 * Méthode qui augmente de 1 le nombre de bouteilles avec $id au cellier
     * @access public
	 * @param int $id de la bouteille
	 * @return Array Tableau des bouteilles retournée
	 */
	public function ajouterQuantiteBouteille($id)
    {
		$oBouteille = new Bouteille;
		$oBouteille->modifierQuantiteBouteilleCellier($id, 1);

		return $this->getBouteilles();
	}

	
	/**
	 * Méthode qui gère les action en DELETE
     * @access public
	 * @param Requete $oReq
	 * @return Mixed Données retournées
	 */
	public function deleteAction(Requete $requete)
	{
		if(isset($requete->url_elements[0]) && is_numeric($requete->url_elements[0]))	// L'id de la bouteille 
			{
				$id_bouteille = (int)$requete->url_elements[0];
				
				if(isset($requete->url_elements[1])) 
				{
					switch($requete->url_elements[1]) 
					{
						case 'quantite':
							$this->retour["data"] = $this->boireQuantiteBouteille($id_bouteille);
							break;
						default:
							$this->retour['erreur'] = $this->erreur(400);
							unset($this->retour['data']);
							break;
					}
				} 
				else
				{
					$this->retour['erreur'] = $this->erreur(400);
					unset($this->retour['data']);
				}
			} 
			else 
			{
				$this->retour['erreur'] = $this->erreur(400);
				unset($this->retour['data']);
			}
		return $this->retour;
	}


	/**
	 * Méthode qui réduit de 1 le nombre de bouteilles avec $id au cellier 
     * @access public
	 * @param int $id de la bouteille
	 * @return Array Tableau des bouteilles retournée
	 */
	public function boireQuantiteBouteille($id)
    {
		$oBouteille = new Bouteille;
		$oBouteille->modifierQuantiteBouteilleCellier($id, -1);
		
		return $this->getBouteilles();
	}

	
	/**
	 * Modifie les informations de la bouteille
	 * @access private
	 * @param Array Les informations de la bouteille
	 * @return int $id Identifiant de la bouteille dans le cellier à modifier
	 */	
	private function modifBouteille($data)
	{
		$res = Array();
		$oBouteille = new Bouteille();
		
		$res = $oBouteille->modifBouteille($data);
		return $res; 
	}
	

    /**
	 * Ajouter une bouteille au cellier
	 * @access private
	 * @param Array Les informations de la bouteille
	 * @return int $id_bouteille Identifiant de la nouvelle bouteille
	 */	
	private function ajouterUneBouteille($data)
	{
		$res = Array();
		$oBouteille = new Bouteille();
		$res = $oBouteille->ajouterBouteilleCellier($data);
		return $res; 
	}

	
    /**
	 * Afficher des erreurs
	 * @access private
	 * @param String Le code d'erreur
	 * @return Array Les message d'erreurs
	 */	
	private function erreur($code, $data="")
	{
		//header('HTTP/1.1 400 Bad Request');
		http_response_code($code);

		return array("message"=>"Erreur de requete", "code"=>$code);
	}

	
    /**
	 * Retourne les informations des bouteilles au cellier	 
     * @access private
	 * @return Array Tableau de toutes les bouteilles au cellier
	 */	
	private function getBouteilles()
	{
		$res = Array();
		$oVino = new Bouteille();
		$res = $oVino->getListeBouteilleCellier();
		
		return $res; 
	}

    
    /**
	 * Retourne liste des bouteilles importées de la SAQ disponibles pour ajouter au cellier. 
	 * @return Array Les informations sur toutes les bouteilles
	 * @access private
	 */	
	private function getBouteillesInserer()
	{
		$res = Array();
		$oBouteille = new Bouteille();
		$res = $oBouteille->getBouteillesInserer();
		
		return $res; 
	}
}
