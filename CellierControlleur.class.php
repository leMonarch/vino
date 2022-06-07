<?php
//TEST
/**
 * Class BiereControleur
 * Controleur de la ressource Biere
 * 
 * @author Jonathan Martel
 * @version 1.1
 * @update 2019-11-11
 * @license MIT
 */

  
class CellierControlleur 
{
	private $retour = array('data'=>array());

	/**
	 * Méthode qui gère les action en GET
	 * @param Requete $requete
	 * @return Mixed Données retournées
	 */
	public function getAction(Requete $requete)
	{
		if(isset($requete->url_elements[0]) && is_numeric($requete->url_elements[0]))	// Normalement l'id du cellier     
		{
			$id_cellier = (int)$requete->url_elements[0];//l'id du cellier
			
			if(isset($requete->url_elements[1]) && !is_numeric($requete->url_elements[1])) //La classe après le l'id
			{
				switch($requete->url_elements[1])
				{
					case 'cellier':
						//Va chercher  les bouteilles d'un cellier précis
						$this->retour["data"] = $this->getBouteillesCellier($id_cellier);
						break;
					default:
						$this->retour['erreur'] = $this->erreur(400);
						unset($this->retour['data']);
						break;
				}
			} 
			else // Retourne les infos d'une bière
			{
				//Pour le moment
				$this->retour['erreur'] = $this->erreur(400);
				unset($this->retour['data']);
			}
		} 
		else 
		{
			// $this->retour["data"] = $this->ajouterQuantiteBouteille(1,10,1);//Pour le premier exemple nous utilisons l'id du cellier 1 et l'id du user 1
			$this->retour["data"] = $this->getBouteilles();
			// 
		}

        return $this->retour;		
		
	}
	
	/**
	 * Méthode qui gère les action en POST
	 * @param Requete $requete
	 * @return Mixed Données retournées
	 */
	public function postAction(Requete $requete)	// Modification
	{
        if(isset($requete->url_elements[0]) && is_numeric($requete->url_elements[0]))	// Normalement l'id du cellier
			{
				$id_cellier = (int)$requete->url_elements[0];
				//0/1       /2 / 3
				//1/cellier/10/bouteille
				if(isset($requete->url_elements[1])) 
				{
					switch($requete->url_elements[1]) 
					{
						case 'cellier':
							if(isset($requete->url_elements[2]) && is_numeric($requete->url_elements[2]))	// Normalement (l'id de la bouteille***) dans le cellier
							{
								$id_bouteille = (int)$requete->url_elements[2];//l'id de la bouteille
								if(isset($requete->url_elements[3])){//La bouteille
									switch($requete->url_elements[3])
									{
										case 'bouteille':
											$this->retour["data"] = $this->modifBouteille($id_cellier,$id_bouteille,$requete->parametres);//Pour le premier exemple nous utilisons l'id du cellier 1 et l'id du user 1
											break;
										default:
											$this->retour['erreur'] = $this->erreur(400);
											unset($this->retour['data']);
											break;
									}
								}
							}
							
							break;
						default:
							$this->retour['erreur'] = $this->erreur(400);
							unset($this->retour['data']);
							break;
					}
				} 
				else // Retourne les infos d'une bière
				{
					$this->retour['erreur'] = $this->erreur(400);
					unset($this->retour['data']);
				}
			} 
			else 
			{
				$this->retour["data"] = $this->getBouteilles();
				
			}
			return $this->retour;
	}






















	
















//Dmitriy
	/**
	 * Méthode qui gère les action en PUT
	 * @param Requete $requete
	 * @return Mixed Données retournées
	 */
	public function putAction(Requete $requete)		//ajout ou modification
	{
	
		// if(!$this->valideAuthentification())
		// {
		// 	$this->retour['erreur'] = $this->erreur(401);
		// }
		// else{
			if(isset($requete->url_elements[0]) && is_numeric($requete->url_elements[0]))	// Normalement l'id du cellier
			{
				$id_cellier = (int)$requete->url_elements[0];
				//0/1       /2 / 3
				//1/cellier/10/bouteille
				if(isset($requete->url_elements[1])) 
				{
					switch($requete->url_elements[1]) 
					{
						case 'cellier':
							if(isset($requete->url_elements[2]) && is_numeric($requete->url_elements[2]))	// Normalement (l'id de la bouteille***) dans le cellier
							{
								$id_bouteille = (int)$requete->url_elements[2];//l'id de la bouteille
								if(isset($requete->url_elements[3])){//La bouteille
									switch($requete->url_elements[3])
									{
										case 'bouteille':
											$this->retour["data"] = $this->ajouterQuantiteBouteille($id_bouteille,$id_cellier);//Pour le premier exemple nous utilisons l'id du cellier 1 et l'id du user 1
											break;
										default:
											$this->retour['erreur'] = $this->erreur(400);
											unset($this->retour['data']);
											break;
									}
								}
							}
							
							break;
						default:
							$this->retour['erreur'] = $this->erreur(400);
							unset($this->retour['data']);
							break;
					}
				} 
				else // Retourne les infos d'une bière
				{
					$this->retour['erreur'] = $this->erreur(400);
					unset($this->retour['data']);
				}
			} 
			else 
			{
				$this->retour["data"] = $this->getBouteilles();
				
			}
		// }
		return $this->retour;
	}

//Louis-Etienne -> Le code avec plusieurs cellier pour un utilisateur et l'ajout de la quantite pour une bouteille de ce cellier de cet utilisateur
// public function ajouterQuantiteBouteille($id_bouteille, $id_cellier, $id_user, $nombre){
// 	$oBouteille = new Bouteille;
// 	// $oBouteille->modifierQuantiteBouteilleCellier($id_cellier,$id_bouteille,$id_user,1);
// 	$oBouteille->modifierQuantiteBouteilleCellier($id_cellier,$id_bouteille,1);
// 	// $data['quantite'] = $oBouteille->getQuantite($id_cellier,$id_bouteille,$id_user); 
// 	$data['quantite'] = $oBouteille->getQuantite($id_cellier,$id_bouteille); 	
// 	return $data['quantite'];
// }
public function ajouterQuantiteBouteille($id_bouteille, $id_cellier){

	$oBouteille = new Bouteille;
	// $oBouteille->modifierQuantiteBouteilleCellier($id_cellier,$id_bouteille,$id_user,1);
	$oBouteille->modifierQuantiteBouteilleCellier($id_cellier,$id_bouteille, 1);
	// $data['quantite'] = $oBouteille->getQuantite($id_cellier,$id_bouteille,$id_user); 
	
	return $this->getBouteilles();
}

// $body = json_decode(file_get_contents('php://input'));
			
// 			$bte = new Bouteille();
// 			$resultat = $bte->modifierQuantiteBouteilleCellier($body->id, 1);
// 			echo json_encode($resultat);

public function getCellierUserId(){
	$oUser = new Usager;
	return $oUser->getCellierUserId();
}
public function getCurrentUserId(){
	$oUser = new Usager;
	return $oUser->getCurrentUserId();
}
public function ajouterCellier($id_user){
	$data = [];
	return $data['cellier'];
}

//Vsevolod


	
	/**
	 * Méthode qui gère les action en DELETE
	 * @param Requete $oReq
	 * @return Mixed Données retournées
	 */
	public function deleteAction(Requete $requete)
	{
		if(isset($requete->url_elements[0]) && is_numeric($requete->url_elements[0]))	// Normalement l'id du cellier
			{
				$id_cellier = (int)$requete->url_elements[0];
				//0/1       /2 / 3
				//1/cellier/10/bouteille
				if(isset($requete->url_elements[1])) 
				{
					switch($requete->url_elements[1]) 
					{
						case 'cellier':
							if(isset($requete->url_elements[2]) && is_numeric($requete->url_elements[2]))	// Normalement (l'id de la bouteille***) dans le cellier
							{
								$id_bouteille = (int)$requete->url_elements[2];//l'id de la bouteille
								if(isset($requete->url_elements[3])){//La bouteille
									switch($requete->url_elements[3])
									{
										case 'bouteille':
											$this->retour["data"] = $this->boireQuantiteBouteille($id_bouteille,$id_cellier);//Pour le premier exemple nous utilisons l'id du cellier 1 et l'id du user 1
											break;
										default:
											$this->retour['erreur'] = $this->erreur(400);
											unset($this->retour['data']);
											break;
									}
								}
							}
							
							break;
						default:
							$this->retour['erreur'] = $this->erreur(400);
							unset($this->retour['data']);
							break;
					}
				} 
				else // Retourne les infos d'une bière
				{
					$this->retour['erreur'] = $this->erreur(400);
					unset($this->retour['data']);
				}
			} 
			else 
			{
				$this->retour["data"] = $this->getBouteilles();
				
			}
		

		return $this->retour;
		
	}

//Bita -> Le code avec plusieurs cellier pour un utilisateur et l'ajout de la quantite pour une bouteille de ce cellier de cet utilisateur
// public function ajouterQuantiteBouteille($id_bouteille, $id_cellier, $id_user, $nombre){
// 	$oBouteille = new Bouteille;
// 	// $oBouteille->modifierQuantiteBouteilleCellier($id_cellier,$id_bouteille,$id_user,1);
// 	$oBouteille->modifierQuantiteBouteilleCellier($id_cellier,$id_bouteille,1);
// 	// $data['quantite'] = $oBouteille->getQuantite($id_cellier,$id_bouteille,$id_user); 
// 	$data['quantite'] = $oBouteille->getQuantite($id_cellier,$id_bouteille); 	
// 	return $data['quantite'];
// }
public function boireQuantiteBouteille($id){

	$oBouteille = new Bouteille;
	// $oBouteille->modifierQuantiteBouteilleCellier($id_cellier,$id_bouteille,$id_user,1);
	$oBouteille->modifierQuantiteBouteilleCellier($id, -1);
	// $data['quantite'] = $oBouteille->getQuantite($id_cellier,$id_bouteille,$id_user); 
	
	return $this->getBouteilles();
}

// $body = json_decode(file_get_contents('php://input'));
			
// 			$bte = new Bouteille();
// 			$resultat = $bte->modifierQuantiteBouteilleCellier($body->id, 1);
// 			echo json_encode($resultat);


















































//Bita


	
	
	
	/**
	 * Retourne les informations de la bière $id_biere
	 * @param int $id_biere Identifiant de la bière
	 * @return Array Les informations de la bière
	 * @access private
	 */	
	private function getBiere($id_biere)
	{
		$res = Array();

		$oBiere = new Biere();
		$res = $oBiere->getBiere($id_biere);
		return $res; 
	}
	
	/**
	 * Retourne les informations des bières de la db	 
	 * @return Array Les informations sur toutes les bières
	 * @access private
	 */	
	private function getListeBiere()
	{
		$res = Array();
		$oBiere = new Biere();
		$res = $oBiere->getListe();
		
		return $res; 
	}
	
	/**
	 * Retourne les commentaires de la bière $id_biere
	 * @param int $id_biere Identifiant de la bière
	 * @return Array Les commentaires de la bière
	 * @access private
	 */	
	private function getCommentaire($id_biere)
	{
		$res = Array();
		$oCommentaire = new Commentaire();
		$res = $oCommentaire->getListe($id_biere);
		$oUsager = new Usager();
		foreach($res as $cle => $valeur){
			$aUsager = $oUsager->getUsagerParId($valeur["id_usager"]);	
			$res[$cle]['courriel'] = $aUsager['courriel'];
			unset($res[$cle]["id_usager"]);
		}
		
		
		return $res; 
	}

	/**
	 * Retourne la note moyenne et le nombre de note de la bière $id_biere
	 * @param int $id_biere Identifiant de la bière
	 * @return Array La note de la bière
	 * @access private
	 */	
	private function getNote($id_biere)
	{
		
		$res = Array();
		$oNote = new Note();
		$res['nombre'] = $oNote->getNombre($id_biere);
		$res['note'] = $oNote->getMoyenne($id_biere);
		$res['id_biere'] = $id_biere;
		return $res; 
	}
	

	/**
	 * Modifie les informations de la bière $id_biere
	 * @param int $id_biere Identifiant de la bière
	 * @param Array Les informations de la bière
	 * @return int $id_biere Identifiant de la bière modifier
	 * @access private
	 */	
	private function modifBouteille($id_bouteille_cellier, $id_bouteille, $data)
	{
		$res = Array();
		$oCellier = new Cellier;
		
		$res = $oCellier->modifBouteille($id_bouteille_cellier, $id_bouteille, $data);
		return $res; 
	}
	
	/**
	 * Effacer la bière $id_biere
	 * @param int $id_biere Identifiant de la bière
	 * @return boolean Succès ou échec
	 * @access private
	 */	
	private function effacerBiere($id_biere)
	{
		$res = Array();
		$oBiere = new Biere();
		
		$res = $oBiere->effacerBiere($id_biere);
		return $res; 
	}

	/**
	 * Ajouter une bière 
	 * @param Array Les informations de la bière
	 * @return int $id_biere Identifiant de la nouvelle bière
	 * @access private
	 */	
	private function ajouterUneBiere($data)
	{
		$res = Array();
		$oBiere = new Biere();
		$res = $oBiere->ajouterBiere($data);
		return $res; 
	}

	/**
	 * Ajouter une bière 
	 * @param int $id_biere Identifiant de la bière
	 * @param Array Les informations sur la note
	 * @return int $id_biere Identifiant de la nouvelle bière
	 * @access private
	 */	
	private function ajouterUneNote($id_biere, $data)
	{
		$res = Array();

		if(isset($data['courriel']))
		{
			$oUsager = new Usager();
			$usager = $oUsager->getUsagerParCourriel($data['courriel']);
			if(!isset($usager))
			{
				$id_usager = $oUsager->ajouterUsager($data['courriel']);
			}
			else{
				$id_usager = (int)$usager['id_usager'];
			}

			$oNote = new Note();
			$res = $oNote->ajouterNote($id_usager, $id_biere, $data["note"]);
		}

		
		
		return $res; 
	}


	/**
	 * Ajouter une bière 
	 * @param int $id_biere Identifiant de la bière
	 * @param Array Les informations du commentaire
	 * @return int $id_biere Identifiant de la nouvelle bière
	 * @access private
	 */	
	private function ajouterUnCommentaire($id_biere, $data)
	{
		$res = Array();

		if(isset($data['courriel']))
		{
			$oUsager = new Usager();
			$usager = $oUsager->getUsagerParCourriel($data['courriel']);
			if(!isset($usager))
			{
				$id_usager = $oUsager->ajouterUsager($data['courriel']);
			}
			else{
				$id_usager = (int)$usager['id_usager'];
			}

			$oCommentaire = new Commentaire();
			$res = $oCommentaire->ajouterCommentaire($id_usager, $id_biere, $data["commentaire"]);
		}
		return $res; 
	}


	/**
	 * Valide les données d'authentification du service web
	 * @return Boolean Si l'authentification est valide ou non
	 * @access private
	 */	
	private function valideAuthentification()
    {
      	$access = false;
		$headers = apache_request_headers();
		
		if(isset($headers['Authorization']) || isset($headers['authorization']))	//Fetch avec Chrome envoie authorization et non Authorization ! 
		{
			if(isset($_SERVER['PHP_AUTH_PW']) && isset($_SERVER['PHP_AUTH_USER']))
			{
				if($_SERVER['PHP_AUTH_PW'] == 'biero' && $_SERVER['PHP_AUTH_USER'] == 'biero')
				{
					$access = true;
				}
			}
		}
      	return $access;
    }

	
	private function erreur($code, $data="")
	{
		//header('HTTP/1.1 400 Bad Request');
		http_response_code($code);

		return array("message"=>"Erreur de requete", "code"=>$code);
		
	}

	//*********************************CODE VINO */ pour URL localhost/id_cellier/cellier/id_bouteille/bouteille

		/**
	 * Retourne les informations des bières de la db	 
	 * @return Array Les informations sur toutes les bières
	 * @access private
	 */	
	private function getBouteillesCellier($id_cellier)
	{
		$res = Array();
		$oVino = new Cellier();
		$res = $oVino->getListeBouteillesCellier($id_cellier);
		
		return $res; 
	}

	//*********************************CODE VINO */ pour URL localhost/cellier/ sans id position 0

		/**
	 * Retourne les informations des bières de la db	 
	 * @return Array Les informations sur toutes les bières
	 * @access private
	 */	
	private function getBouteilles() 
	{
		$res = Array();
		$oVino = new Cellier();
		$res = $oVino->getListeBouteilleCellier();
		
		return $res; 
	}

}
