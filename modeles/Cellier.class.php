<?php
/**
 * Class Bouteille
 * Cette classe possède les fonctions de gestion des bouteilles dans le cellier et des bouteilles dans le catalogue complet.
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 */
class Cellier extends Modele {
	const TABLE = 'vino__bouteille';
    
	public function getListeBouteille()
	{
		
		$rows = Array();
		$res = $this->_db->query('Select * from '. self::TABLE);
		if($res->num_rows)
		{
			while($row = $res->fetch_assoc())
			{
				$rows[] = $row;
			}
		}
		// var_dump($rows); die;
		//Liste bouteille
		return $rows;
	}

	public function getListeBouteilleCellier()
	{
		
		$rows = Array();
		$requete ='SELECT 
						c.id as id_bouteille_cellier,
						c.id_bouteille, 
						c.date_achat, 
						c.garde_jusqua, 
						c.notes, 
						c.prix, 
						c.quantite,
						c.millesime, 
						b.id,
						b.nom, 
						b.type, 
						b.image, 
						b.code_saq, 
						b.url_saq, 
						b.pays, 
						b.description,
						t.type 
						from vino__cellier c 
						INNER JOIN vino__bouteille b ON c.id_bouteille = b.id
						INNER JOIN vino__type t ON t.id = b.type';
		if(($res = $this->_db->query($requete)) ==	 true)
		{
			if($res->num_rows)
			{
				while($row = $res->fetch_assoc())
				{
					$row['nom'] = trim(utf8_encode($row['nom']));
					$rows[] = $row;
				}
			}
		}
		else 
		{
			throw new Exception("Erreur de requête sur la base de donnée", 1);
			 //$this->_db->error;
		}
		
		
		
		return $rows;
	}
	
	public function getListeBouteillesCellier($id_cellier)
	{
		
		$rows = Array();
		$requete ='SELECT 
						c.id as id_bouteille_cellier,
						c.id_bouteille, 
						c.date_achat, 
						c.garde_jusqua, 
						c.notes, 
						c.prix, 
						c.quantite,
						c.millesime, 
						b.id,
						b.nom, 
						b.type, 
						b.image, 
						b.code_saq, 
						b.url_saq, 
						b.pays, 
						b.description,
						t.type 
						from vino__cellier c 
						INNER JOIN vino__bouteille b ON c.id_bouteille = b.id
						INNER JOIN vino__type t ON t.id = b.type
						WHERE c.id = '. $id_cellier;
		if(($res = $this->_db->query($requete)) ==	 true)
		{
			if($res->num_rows)
			{
				while($row = $res->fetch_assoc())
				{
					$row['nom'] = trim(utf8_encode($row['nom']));
					$rows[] = $row;
				}
			}
		}
		else 
		{
			throw new Exception("Erreur de requête sur la base de donnée", 1);
			 //$this->_db->error;
		}
		
		
		
		return $rows;
	}
	
	/**
	 * Cette méthode permet de retourner les résultats de recherche pour la fonction d'autocomplete de l'ajout des bouteilles dans le cellier
	 * 
	 * @param string $nom La chaine de caractère à rechercher
	 * @param integer $nb_resultat Le nombre de résultat maximal à retourner.
	 * 
	 * @throws Exception Erreur de requête sur la base de données 
	 * 
	 * @return array id et nom de la bouteille trouvée dans le catalogue
	 */
       
	public function autocomplete($nom, $nb_resultat=10)
	{
		
		$rows = Array();
		$nom = $this->_db->real_escape_string($nom);
		$nom = preg_replace("/\*/","%" , $nom);
		 
		//echo $nom;
		$requete ='SELECT id, nom FROM vino__bouteille where LOWER(nom) like LOWER("%'. $nom .'%") LIMIT 0,'. $nb_resultat; 
		//var_dump($requete);
		if(($res = $this->_db->query($requete)) ==	 true)
		{
			if($res->num_rows)
			{
				while($row = $res->fetch_assoc())
				{
					$row['nom'] = trim(utf8_encode($row['nom']));
					$rows[] = $row;
					
				}
			}
		}
		else 
		{
			throw new Exception("Erreur de requête sur la base de données", 1);
			 
		}
		
		
		//var_dump($rows);
		return $rows;
	}
	
	
	/**
	 * Cette méthode ajoute une ou des bouteilles au cellier
	 * 
	 * @param Array $data Tableau des données représentants la bouteille.
	 * 
	 * @return Boolean Succès ou échec de l'ajout.
	 */
	public function ajouterBouteilleCellier($data)
	{
		//TODO : Valider les données.
		//var_dump($data);	
		
		$requete = "INSERT INTO vino__cellier(id_bouteille,date_achat,garde_jusqua,notes,prix,quantite,millesime) VALUES (".
		"'".$data->id_bouteille."',".
		"'".$data->date_achat."',".
		"'".$data->garde_jusqua."',".
		"'".$data->notes."',".
		"'".$data->prix."',".
		"'".$data->quantite."',".
		"'".$data->millesime."')";

        $res = $this->_db->query($requete);
        
		return $res;
	}
	
	
	/**
	 * Cette méthode change la quantité d'une bouteille en particulier dans le cellier
	 * 
	 * @param int $id id de la bouteille
	 * @param int $nombre Nombre de bouteille a ajouter ou retirer
	 * 
	 * @return Boolean Succès ou échec de l'ajout.
	 */
	// public function modifierQuantiteBouteilleCellier($id_cellier, $id_bouteille, $id_user, $nombre) //$id_cellier??? et $id_bouteille
	// {
	// 	//TODO : Valider les données.
			
			
	// 	$requete = "UPDATE vino__cellier SET quantite = GREATEST(quantite + ". $nombre. ", 0) WHERE id = ". $id_cellier ."AND id_bouteille = ".$id_bouteille
	// 																									."AND id_user = ".$id_user;
	// 	//echo $requete;
    //     $res = $this->_db->query($requete);
        
	// 	return $res;
	// }

	// public function getQuantite($id_cellier, $id_bouteille, $id_user){
	// 	$requete = "SELECT quantite FROM vino__celiier WHERE id = ". $id_cellier ."AND id_bouteille = ".$id_bouteille
	// 													."AND id_user = ".$id_user;
	// 	//echo $requete;
    //     return $this->_db->query($requete);
	// }

	public function modifierQuantiteBouteilleCellier($id, $nombre) //$id_cellier??? et $id_bouteille
	{
		//TODO : Valider les données.
		$qte = $this->getQuantite($id);	
		if($qte > 0 && $nombre == -1){
			$requete = "UPDATE vino__cellier SET quantite = GREATEST(quantite + ". $nombre. ", 0) WHERE id = ". $id .";";
			//echo $requete;
			$res = $this->_db->query($requete);
			
			return $res;
		} else if($qte >= 0 && $nombre == 1){
			$requete = "UPDATE vino__cellier SET quantite = GREATEST(quantite + ". $nombre. ", 0) WHERE id = ". $id .";";
			//echo $requete;
			$res = $this->_db->query($requete);
			
			return $res;
		}
		return 0;
		
	}

	public function getQuantite($id){
		$requete = "SELECT quantite FROM vino__celiier WHERE id = ". $id .";";
		//echo $requete;
        return $this->_db->query($requete);
	}


    /**
	 * Cette méthode modifie la bouteille
	 * @access public
	 * @param int $id Identifiant de la bière
	 * @param Array $param Paramètres et valeur à modifier 
	 * @return int id de la bouteille ou 0 en cas d'échec
	 */
	public function modifBouteille($id, $param)	
	{
		$aSet = Array();
		$resQuery = false;
		foreach ($param as $cle => $valeur) {
			$aSet[] = ($cle . "= '".$valeur. "'");
		}
		if(count($aSet) > 0)
		{
			$query = "Update vino__cellier SET ";
			$query .= join(", ", $aSet);
			
			$query .= (" WHERE id = ". $id); 
			$resQuery = $this->_db->query($query);
			
		}
		//echo $query;
		return ($resQuery ? $id : 0);
	}
}




?>