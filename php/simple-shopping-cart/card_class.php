<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                                                                                              //
 ###    ####   #####      ##	  #####  #####  ####		####   #   #	     ##      #     #     #####  #####  #####       //
#   #   #   #  #         #  #		#    #      #   #		#   #	# #	        #  #     #           #      #      #            //
#       ####   ###      #	 #      #    ###    #   #		#####	 #		   #    #    #     #     ###    ###    ###          //
#   #   #   #  #       ########     #	 #      #   #	    #   #    #		  ########   #     #     #      #      #            //
 ###    #   #  #####  #		   #	#    #####  ####		####	 #		 #        #  ####  #     #####  #      #####       //
                                                                                                                              //
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //
 //  This shopping card class is created by Ali EFE , e-mail: efeali@gmail.com
 //  Free for use, would appreciate if you mentioned aedevelopment or put link back to www.aedevelopment.net
 //  
 //  Version : 0.3 
 //  Date of create : 05-11-2010
 //  Date of last modified : 19-11-2010
 //  
 //  IF YOU FIND ANY BUG PLEASE REPORT TO : info@aedevelopment.net   or   efeali@gmail.com 
 //
 ///////////////////////////////////////////////////////////  
 //
 //  ### HOW TO USE: ####
 //
 //   *First you should create card object 
 //    ex. $card = new shopping_card();
 //
 //   From now if we consider our card is $card :
 //   
 //     -To add product:  $card->addToCard(product id,product name,price,qty,color,size);	*make sure you are sending 6 parameters. 
 //                      This function will add your product in basket but will not save in a session. If the product already existed in your card 
 //                      (should be same product id (also same color and size if its defined)) then it will update quantity. If it is not existed then it will //                      add this item in your card.
 //                      
 //                      Parameters: 
 //                                 product id = should be just number
 //                                 product name = is string
 //                                 price = should be numeric , no $ sign , you can use "." example: 12.40
 //                                 qty = quantity , should be numeric
 //                                 color = is string, if you don't want to set then use "NULL"
 //                                 size = is string, if you don't want to set then use "NULL"
 //
 //                      ex. $card->addToCard(2, "coffe cup","12.00",3,NULL,NULL);	
 //     
 //     -To get products from card: $card->getCard();
 //                                 
 //                                 This function will return products in card. Return value is an array
 //                                 Basically our card has these fields below:
 //                                   - item_id   (this id is unique id for each item added into card)
 // 								  - prod_id
 //									  - name
 //									  - price
 //									  - qty
 // 								  - color
 //									  - size
 //
 //     -To clean the card: $card->clearCard();
 //
 //     -To delete 1 item from card: $card->deleteItem(item id);    ** this item id is different than product id.
 //
 //		-To get total cost of card: $card->getTotalCost();    
 //
 //
 //////////////////////////////////////////////////////////////
 
class shopping_card
{
	private $card;
	private $item;

	
	//////// find a product by in our card by using item_id or prod_id(product id). parameters : prod_id, the key that search will perform(item_id or prod_id) 
	private function findProduct($id,$key)  /// if it found a product returns array of address number else -1
	{
		$error = -1;	// we are setting this if our search can't find matching information, then we will return -1 , which means no result
		$item_no = array();
		$number = count($this->card);
		for($i=0;$i<$number;$i++)
		{
			if($this->card[$i][$key]==$id)
			{
				array_push($item_no,$i);
				$error = $i;
			}
		}
		if($error==-1)
			return -1;
		else
			return $item_no;
	}
	
	
	
	/////// returns card array
	public function getCard()		
	{
		return $this->card;
	}
	
	////////// set card array
	protected function setCard($data)	
	{
		$this->card = $data;
	}
	
	//////////// add new product in our card
	public function addToCard($id,$name,$price,$qty,$color,$size)	
	{
		if(is_numeric($id) && (is_string($name) && !is_numeric($name)) && is_numeric($price) && is_numeric($qty)) /// make sure we are getting correct type of variables
		{
			$item_no = $this->findProduct($id,'prod_id');		//2nd parameter : we will search in product prod_id's
			if($item_no == -1) // if this product not existed in our card
			{
					
				$num_card = count($this->card)-1;
				$id_max = 0;
				function getMax($id_max,$value)
				{
					if($value > $id_max) $id_max = $value;
					return $id_max;
				}
				foreach($this->card as $key=>$value)
				{
					$id_max = getMax($id_max,$value['item_id']);
				
				}
				$num_prod = $id_max+1;

				$this->item = array("item_id"=>$num_prod,"prod_id"=>$id,"name"=>$name,"price"=>$price,"qty"=>$qty,"color"=>$color,"size"=>$size);
				array_push($this->card,$this->item);
			}
			else   ////    if we already have this product , array will return 
			{	//item_no is an array in this case
				for($i=0;$i<count($item_no);$i++)
				{
					if(($this->card[$item_no[$i]]["color"] == $color) && ($this->card[$item_no[$i]]["size"] == $size))
					{
						$this->card[$item_no[$i]]["qty"] = $this->card[$item_no[$i]]["qty"] + $qty;    /// update quantity
						$match = true;
						break;
					}
				}
				if(!isset($match))
				{
					$num_prod = count($this->card)+1;
					$this->item = array("item_id"=>$num_prod,"prod_id"=>$id,"name"=>$name,"price"=>$price,"qty"=>$qty,"color"=>$color,"size"=>$size);
					array_push($this->card,$this->item);
				}
				
			}
			return 1;
		}
		else
		{
			$error_check = "";
			if(!is_numeric($id)) $error_check .="id has to be numeric<br/>";
			if(!is_string($name) || is_numeric($name)) $error_check .="Product name has to be string <br/>"; //// if it doesn't have characters inside
			if(!is_numeric($price)) $error_check .="Product's price should be numeric <br/>";
			if(!is_numeric($qty)) $error_check .="Quantity should be number";
			if(!is_null($color) && !is_string($color)) $error_check .="Color should be text";
			return $error_check;
		}
	}
	
	//////// clear card
	public function clearCard()		
	{
		$this->card = array();
		unset($_SESSION['card']);
	}
	
	/////// delete spesific product from card by using id which is unique
	public function deleteItem($id)  
	{
		$row_num = $this->findProduct($id,'item_id');   /// 2nd parameter: we will search in item_id's (which will be exact item match)
		if($row_num == -1)
			return false;
		else
		{
			unset($this->card[$row_num[0]]);
			$this->card = array_values($this->card);
			return true;
		}

	}
	
	////// calculate total cost of current card, returns number or -1 for empty card
	public function getTotalCost()  
	{
		if(empty($this->card)) 			/// if the card is empty we return -1
		{
			return -1;	
		}
		else
		{
			$total = 0;
			foreach($this->card as $data)
			{
				$total =$total + ($data['price']*$data['qty']);
			}
			unset($data);
			return $total;
		}
	}
	/////////////////////////////////////////////////      construct and destruct
	function __construct()
	{
		if(session_id()!="")  // check if we started session in our main code
		{
			$this->card = array();
			$this->item = array("item_id","prod_id","name","price","qty","color","size");
			
			if(isset($_SESSION['card']) && $_SESSION['card']!="")
			{
				$this->setCard($_SESSION['card']);
			}
	
		}
		else
		{
			echo "You should start a session.Please add session_start() in your main code!<br/>";	
		}
	}
	
	function __destruct()
	{
		$_SESSION['card'] = $this->card;
	}
	
}






?>