<?php include("includes/functions.php");

$manage = new management();
if($manage->authCheck())
{
	include("header.php"); 
	
?>

<!--<link href="styles/main.css" type="text/css" rel="stylesheet" />-->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />



	<div id="main">
    <?php 
	if(isset($_GET['done']) || isset($_GET['fail']))
	{
		if(isset($_GET['done']) && isset($_SESSION['user_info']))
		{
			echo "A new registrant added succesfully.<br/>";
			echo "UID :".$_SESSION['user_info']['id']."<br/>";
			echo "User pass :".$_SESSION['user_info']['pass']."<br/>";
			echo "ID Confirmation password : ".$_SESSION['user_info']['temp_pass']."<br/>";
			unset($_SESSION['user_info']);
		}
		elseif(isset($_GET['fail']))
		{
			echo "There was an error.<Br/>Error : ".$_SESSION['error'];
			unset($_SESSION['error']);
		}
	}
	else
	{
		?>
    	<fieldset ><legend>Enter a new registrant information</legend>
    	<form enctype="multipart/form-data" action="handler.php" method="post">
        	<input type="hidden" name="action" value="new_register" />
            <table width="600" id="register_table">
            	<tr><th colspan="2" class="thtitle">Personal Information</th></tr>
                <tr><th><label>First Name</label></th>
                	<td><span id="sprytextfield1">
                	<input type="text" name="f_name" class="textBox1" maxlength="100" /><br/>
              <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></td></tr>
                <tr><th><label>Last Name</label></th>
                	<td><span id="sprytextfield2">
                  	<input type="text" name="l_name" class="textBox1" maxlength="100" /><br/>
              <span class="textfieldRequiredMsg">A value is required.</span></span></td></tr>
                <tr><th><label>Middle Name</label></th>
                	<td><span id="sprytextfield3">
                  	<input type="text" name="m_name" class="textBox1" maxlength="100" /><br/>
</span></td></tr>
                <tr><th><label>Gender</label></th>
                	<td>
                  	<span id="spryradio1">
                  	<label>
                  	  <input type="radio" name="gender" value="1" id="RadioGroup1_0" />
                  	  Male</label>
                  	<br />
                  	<label>
                  	  <input type="radio" name="gender" value="0" id="RadioGroup1_1" />
                  	  Female</label>
                  	<br />
              <span class="radioRequiredMsg">Please make a selection.</span></span></td></tr>
            	<tr><th><label>Height</label></th>
                	<td><span id="sprytextfield4">
                	<input type="text" name="height" class="textBox2" maxlength="3" /><br/>
              		<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td></tr>
            	<tr><th><label>Month and Year of Birth</label></th>

					<td>Month<span id="spryselect1"><select name="birth_month" id="select1">
                    				<option value="" selected="selected">Select</option>
                            	
                            		<?php
										for($i=1;$i<=12;$i++)
										{
											?><option value="<?php printf("%02d",$i); ?>"><?php printf("%02d",$i); ?></option><?php
										}
                            		?>
                            	</select>
                                <br/>
                                <span class="selectRequiredMsg">Please select an item.</span></span>
                                <br/>
                                Year
                    <span id="spryselect2"><select name="birth_year" id="select2">
									<option value="" selected="selected">Select</option>
                                	<?php
										for($i=1920;$i<=2012;$i++)
										{
											?><option value="<?php printf("%04d",$i); ?>"><?php printf("%04d",$i); ?></option><?php
									
										}
									?>
                            	</select><br/>
                                <span class="selectRequiredMsg">Please select an item.</span></span>
                  </td>
                </tr>
                <tr><th><label>Place of Birth</label></th>
                		<td>
                        	Town / Suburb / City :<br/>
               	    <span id="sprytextfield5">
                    <input type="text" name="birth_city" maxlength="50" class="textBox1" /><br/>
                  <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span><Br/>
                            State / Province /Prefecture / Canton :<br/>
                    <span id="sprytextfield6">
                    <input type="text" name="birth_province" maxlength="50" class="textBox1" /><br/>
<span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span><br/>
                        	
<span id="spryselect3">
                            <select name="birth_country">
                                <option value="">Country...</option>
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Antigua &amp; Deps">Antigua &amp; Deps</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bosnia Herzegovina">Bosnia Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina">Burkina</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Cape Verde">Cape Verde</option>
                                <option value="Central African Rep">Central African Rep</option>
                                <option value="Chad">Chad</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Congo {Democratic Rep}">Congo {Democratic Rep}</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic">Czech Republic</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="East Timor">East Timor</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Estonia">Estonia</option>
                                <option value="Ethiopia">Ethiopia</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Greece">Greece</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Guinea">Guinea</option>
                                <option value="Guinea-Bissau">Guinea-Bissau</option>
                                <option value="Guyana">Guyana</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="India">India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Iran">Iran</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland {Republic}">Ireland {Republic}</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Ivory Coast">Ivory Coast</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japan">Japan</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kazakhstan">Kazakhstan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kiribati">Kiribati</option>
								<option value="Kosovo">Kosovo</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                <option value="Laos">Laos</option>
                                <option value="Latvia">Latvia</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Lesotho">Lesotho</option>
                                <option value="Liberia">Liberia</option>
                                <option value="Libya">Libya</option>
                                <option value="Liechtenstein">Liechtenstein</option>
                                <option value="Lithuania">Lithuania</option>
                                <option value="Luxembourg">Luxembourg</option>
                                <option value="Macedonia">Macedonia</option>
                                <option value="Madagascar">Madagascar</option>
                                <option value="Malawi">Malawi</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Mali">Mali</option>
                                <option value="Malta">Malta</option>
                                <option value="Marshall Islands">Marshall Islands</option>
                                <option value="Mauritania">Mauritania</option>
                                <option value="Mauritius">Mauritius</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Micronesia">Micronesia</option>
                                <option value="Moldova">Moldova</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montenegro">Montenegro</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Mozambique">Mozambique</option>
                                <option value="Myanmar, {Burma}">Myanmar, {Burma}</option>
                                <option value="Namibia">Namibia</option>
                                <option value="Nauru">Nauru</option>
                                <option value="Nepal">Nepal</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nicaragua">Nicaragua</option>
                                <option value="Niger">Niger</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="Norway">Norway</option>
                                <option value="North Korea">North Korea</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Palau">Palau</option>
                                <option value="Panama">Panama</option>
                                <option value="Papua New Guinea">Papua New Guinea</option>
                                <option value="Paraguay">Paraguay</option>
                                <option value="Peru">Peru</option>
                                <option value="Philippines">Philippines</option>
                                <option value="Poland">Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Romania">Romania</option>
                                <option value="Russian Federation">Russian Federation</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="St Kitts &amp; Nevis">St Kitts &amp; Nevis</option>
                                <option value="St Lucia">St Lucia</option>
                                <option value="Saint Vincent &amp; the Grenadines">Saint Vincent &amp; the Grenadines</option>
                                <option value="Samoa">Samoa</option>
                                <option value="San Marino">San Marino</option>
                                <option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Senegal">Senegal</option>
                                <option value="Serbia">Serbia</option>
                                <option value="Seychelles">Seychelles</option>
                                <option value="Sierra Leone">Sierra Leone</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Slovakia">Slovakia</option>
                                <option value="Slovenia">Slovenia</option>
                                <option value="Solomon Islands">Solomon Islands</option>
                                <option value="Somalia">Somalia</option>
                                <option value="South Africa">South Africa</option>
                                <option value="South Korea">South Korea</option>
                                <option value="Spain">Spain</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Suriname">Suriname</option>
                                <option value="Swaziland">Swaziland</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syria">Syria</option>
                                <option value="Taiwan">Taiwan</option>
                                <option value="Tajikistan">Tajikistan</option>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Togo">Togo</option>
                                <option value="Tonga">Tonga</option>
                                <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Turkmenistan">Turkmenistan</option>
                                <option value="Tuvalu">Tuvalu</option>
                                <option value="Uganda">Uganda</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                                <option value="Uruguay">Uruguay</option>
                                <option value="Uzbekistan">Uzbekistan</option>
                                <option value="Vanuatu">Vanuatu</option>
                                <option value="Vatican City">Vatican City</option>
                                <option value="Venezuela">Venezuela</option>
                                <option value="Vietnam">Vietnam</option>
                                <option value="Yemen">Yemen</option>
                                <option value="Zambia">Zambia</option>
                                <option value="Zimbabwe">Zimbabwe</option>

                            </select><br/>
                                <span class="selectRequiredMsg">Please select an item.</span></span>
                        </td>
                </tr>
                <tr><th><label>Nationality at Birth</label></th>
                		<td>
<span id="spryselect4">
                        	<select name="birth_nationality">
                            	<option value="">Nationality</option>
                            	<option value="Afghan">Afghan</option>
                                <option value="Albanian">Albanian</option>
                                <option value="Algerian">Algerian</option>
                                <option value="American">American</option>
                                <option value="Andorran">Andorran</option>
                                <option value="Angolan">Angolan</option>
                                <option value="Antiguans">Antiguans</option>
                                <option value="Argentinean">Argentinean</option>
                                <option value="Armenian">Armenian</option>
                                <option value="Australian">Australian</option>
                                <option value="Austrian">Austrian</option>
                                <option value="Azerbaijani">Azerbaijani</option>
                                <option value="Bahamian">Bahamian</option>
                                <option value="Bahraini">Bahraini</option>
                                <option value="Bangladeshi">Bangladeshi</option>
                                <option value="Barbadian">Barbadian</option>
                                <option value="Barbudans">Barbudans</option>
                                <option value="Batswana">Batswana</option>
                                <option value="Belarusian">Belarusian</option>
                                <option value="Belgian">Belgian</option>
                                <option value="Belizean">Belizean</option>
                                <option value="Beninese">Beninese</option>
                                <option value="Bhutanese">Bhutanese</option>
                                <option value="Bolivian">Bolivian</option>
                                <option value="Bosnian">Bosnian</option>
                                <option value="Brazilian">Brazilian</option>
                                <option value="British">British</option>
                                <option value="Bruneian">Bruneian</option>
                                <option value="Bulgarian">Bulgarian</option>
                                <option value="Burkinabe">Burkinabe</option>
                                <option value="Burmese">Burmese</option>
                                <option value="Burundian">Burundian</option>
                                <option value="Cambodian">Cambodian</option>
                                <option value="Cameroonian">Cameroonian</option>
                                <option value="Canadian">Canadian</option>
                                <option value="Cape Verdean">Cape Verdean</option>
                                <option value="Central African">Central African</option>
                                <option value="Chadian">Chadian</option>
                                <option value="Chilean">Chilean</option>
                                <option value="Chinese">Chinese</option>
                                <option value="Colombian">Colombian</option>
                                <option value="Comoran">Comoran</option>
                                <option value="Congolese">Congolese</option>
                                <option value="Costa Rican">Costa Rican</option>
                                <option value="Croatian">Croatian</option>
                                <option value="Cuban">Cuban</option>
                                <option value="Cypriot">Cypriot</option>
                                <option value="Czech">Czech</option>
                                <option value="Danish">Danish</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominican">Dominican</option>
                                <option value="Dutch">Dutch</option>
                                <option value="East Timorese">East Timorese</option>
                                <option value="Ecuadorean">Ecuadorean</option>
                                <option value="Egyptian">Egyptian</option>
                                <option value="Emirian">Emirian</option>
                                <option value="Equatorial Guinean">Equatorial Guinean</option>
                                <option value="Eritrean">Eritrean</option>
                                <option value="Estonian">Estonian</option>
                                <option value="Ethiopian">Ethiopian</option>
                                <option value="Fijian">Fijian</option>
                                <option value="Filipino">Filipino</option>
                                <option value="Finnish">Finnish</option>
                                <option value="French">French</option>
                                <option value="Gabonese">Gabonese</option>
                                <option value="Gambian">Gambian</option>
                                <option value="Georgian">Georgian</option>
                                <option value="German">German</option>
                                <option value="Ghanaian">Ghanaian</option>
                                <option value="Greek">Greek</option>
                                <option value="Grenadian">Grenadian</option>
                                <option value="Guatemalan">Guatemalan</option>
                                <option value="Guinea-Bissauan">Guinea-Bissauan</option>
                                <option value="Guinean">Guinean</option>
                                <option value="Guyanese">Guyanese</option>
                                <option value="Haitian">Haitian</option>
                                <option value="Herzegovinian">Herzegovinian</option>
                                <option value="Honduran">Honduran</option>
                                <option value="Hungarian">Hungarian</option>
                                <option value="I-Kiribati">I-Kiribati</option>
                                <option value="Icelander">Icelander</option>
                                <option value="Indian">Indian</option>
                                <option value="Indonesian">Indonesian</option>
                                <option value="Iranian">Iranian</option>
                                <option value="Iraqi">Iraqi</option>
                                <option value="Irish">Irish</option>
                                <option value="Israeli">Israeli</option>
                                <option value="Italian">Italian</option>
                                <option value="Ivorian">Ivorian</option>
                                <option value="Jamaican">Jamaican</option>
                                <option value="Japanese">Japanese</option>
                                <option value="Jordanian">Jordanian</option>
                                <option value="Kazakhstani">Kazakhstani</option>
                                <option value="Kenyan">Kenyan</option>
                                <option value="Kittian and Nevisian">Kittian and Nevisian</option>
                                <option value="Kuwaiti">Kuwaiti</option>
                                <option value="Kyrgyz">Kyrgyz</option>
                                <option value="Laotian">Laotian</option>
                                <option value="Latvian">Latvian</option>
                                <option value="Lebanese">Lebanese</option>
                                <option value="Liberian">Liberian</option>
                                <option value="Libyan">Libyan</option>
                                <option value="Liechtensteiner">Liechtensteiner</option>
                                <option value="Lithuanian">Lithuanian</option>
                                <option value="Luxembourger">Luxembourger</option>
                                <option value="Macedonian">Macedonian</option>
                                <option value="Malagasy">Malagasy</option>
                                <option value="Malawian">Malawian</option>
                                <option value="Malaysian">Malaysian</option>
                                <option value="Maldivan">Maldivan</option>
                                <option value="Malian">Malian</option>
                                <option value="Maltese">Maltese</option>
                                <option value="Marshallese">Marshallese</option>
                                <option value="Mauritanian">Mauritanian</option>
                                <option value="Mauritian">Mauritian</option>
                                <option value="Mexican">Mexican</option>
                                <option value="Micronesian">Micronesian</option>
                                <option value="Moldovan">Moldovan</option>
                                <option value="Monacan">Monacan</option>
                                <option value="Mongolian">Mongolian</option>
                                <option value="Moroccan">Moroccan</option>
                                <option value="Mosotho">Mosotho</option>
                                <option value="Motswana">Motswana</option>
                                <option value="Mozambican">Mozambican</option>
                                <option value="Namibian">Namibian</option>
                                <option value="Nauruan">Nauruan</option>
                                <option value="Nepalese">Nepalese</option>
                                <option value="New Zealander">New Zealander</option>
                                <option value="Nicaraguan">Nicaraguan</option>
                                <option value="Nigerian">Nigerian</option>
                                <option value="Nigerien">Nigerien</option>
                                <option value="North Korean">North Korean</option>
                                <option value="Northern Irish">Northern Irish</option>
                                <option value="Norwegian">Norwegian</option>
                                <option value="Omani">Omani</option>
                                <option value="Pakistani">Pakistani</option>
                                <option value="Palauan">Palauan</option>
                                <option value="Panamanian">Panamanian</option>
                                <option value="Papua New Guinean">Papua New Guinean</option>
                                <option value="Paraguayan">Paraguayan</option>
                                <option value="Peruvian">Peruvian</option>
                                <option value="Polish">Polish</option>
                                <option value="Portuguese">Portuguese</option>
                                <option value="Qatari">Qatari</option>
                                <option value="Romanian">Romanian</option>
                                <option value="Russian">Russian</option>
                                <option value="Rwandan">Rwandan</option>
                                <option value="Saint Lucian">Saint Lucian</option>
                                <option value="Salvadoran">Salvadoran</option>
                                <option value="Samoan">Samoan</option>
                                <option value="San Marinese">San Marinese</option>
                                <option value="Sao Tomean">Sao Tomean</option>
                                <option value="Saudi">Saudi</option>
                                <option value="Scottish">Scottish</option>
                                <option value="Senegalese">Senegalese</option>
                                <option value="Serbian">Serbian</option>
                                <option value="Seychellois">Seychellois</option>
                                <option value="Sierra Leonean">Sierra Leonean</option>
                                <option value="Singaporean">Singaporean</option>
                                <option value="Slovakian">Slovakian</option>
                                <option value="Slovenian">Slovenian</option>
                                <option value="Solomon Islander">Solomon Islander</option>
                                <option value="Somali">Somali</option>
                                <option value="South African">South African</option>
                                <option value="South Korean">South Korean</option>
                                <option value="Spanish">Spanish</option>
                                <option value="Sri Lankan">Sri Lankan</option>
                                <option value="Sudanese">Sudanese</option>
                                <option value="Surinamer">Surinamer</option>
                                <option value="Swazi">Swazi</option>
                                <option value="Swedish">Swedish</option>
                                <option value="Swiss">Swiss</option>
                                <option value="Syrian">Syrian</option>
                                <option value="Taiwanese">Taiwanese</option>
                                <option value="Tajik">Tajik</option>
                                <option value="Tanzanian">Tanzanian</option>
                                <option value="Thai">Thai</option>
                                <option value="Togolese">Togolese</option>
                                <option value="Tongan">Tongan</option>
                                <option value="Trinidadian or Tobagonian">Trinidadian or Tobagonian</option>
                                <option value="Tunisian">Tunisian</option>
                                <option value="Turkish">Turkish</option>
                                <option value="Tuvaluan">Tuvaluan</option>
                                <option value="Ugandan">Ugandan</option>
                                <option value="Ukrainian">Ukrainian</option>
                                <option value="Uruguayan">Uruguayan</option>
                                <option value="Uzbekistani">Uzbekistani</option>
                                <option value="Venezuelan">Venezuelan</option>
                                <option value="Vietnamese">Vietnamese</option>
                                <option value="Welsh">Welsh</option>
                                <option value="Yemenite">Yemenite</option>
                                <option value="Zambian">Zambian</option>
                                <option value="Zimbabwean">Zimbabwean</option>
                            </select><br/>
                            <span class="selectRequiredMsg">Please select an item.</span></span>
                        </td>
                </tr>
                <tr><th><label>Photo 1</label></th><td><input type="file" name="photo1" class="textBox1" /></td></tr>
                <tr><th><label>Photo 2</label></th><td><input type="file" name="photo2" class="textBox1" /></td></tr>
                <tr><th><label>Last 3 Digits of<br/> National ID Number</label></th>
                	<td><span id="sprytextarea1">
                	  <textarea name="national_id_num" class="textBox1"></textarea><br/>
              			<span class="textareaRequiredMsg">A value is required.</span></span></td></tr>
                <tr><th><label>Identity Verification Level</label></th>
                	<td><span id="sprytextfield7">
                	<input type="text" name="id_ver_level" class="textBox2" maxlength="3" /><br/>
              		<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></td></tr>
                
                <tr><th colspan="2" class="thtitle">Current Information</th></tr>
                <tr><th><label>1st Email</label></th>
                	<td><span id="sprytextfield8">
                	<input type="text" name="email1" maxlength="70" /><br/>
              		<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td></tr>
                <tr><th><label>2nd Email</label></th>
                	<td><input type="text" name="email2" maxlength="70" /></td></tr>
                <tr><th><label>Citizen of</label></th>
                		<td>
<span id="spryselect5">
                        	<select name="citizen_country">
                                <option value="">Country...</option>
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Antigua &amp; Deps">Antigua &amp; Deps</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bosnia Herzegovina">Bosnia Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina">Burkina</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Cape Verde">Cape Verde</option>
                                <option value="Central African Rep">Central African Rep</option>
                                <option value="Chad">Chad</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Congo {Democratic Rep}">Congo {Democratic Rep}</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic">Czech Republic</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="East Timor">East Timor</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Estonia">Estonia</option>
                                <option value="Ethiopia">Ethiopia</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Greece">Greece</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Guinea">Guinea</option>
                                <option value="Guinea-Bissau">Guinea-Bissau</option>
                                <option value="Guyana">Guyana</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="India">India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Iran">Iran</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland {Republic}">Ireland {Republic}</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Ivory Coast">Ivory Coast</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japan">Japan</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kazakhstan">Kazakhstan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kiribati">Kiribati</option>
								<option value="Kosovo">Kosovo</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                <option value="Laos">Laos</option>
                                <option value="Latvia">Latvia</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Lesotho">Lesotho</option>
                                <option value="Liberia">Liberia</option>
                                <option value="Libya">Libya</option>
                                <option value="Liechtenstein">Liechtenstein</option>
                                <option value="Lithuania">Lithuania</option>
                                <option value="Luxembourg">Luxembourg</option>
                                <option value="Macedonia">Macedonia</option>
                                <option value="Madagascar">Madagascar</option>
                                <option value="Malawi">Malawi</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Mali">Mali</option>

                                <option value="Malta">Malta</option>
                                <option value="Marshall Islands">Marshall Islands</option>
                                <option value="Mauritania">Mauritania</option>
                                <option value="Mauritius">Mauritius</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Micronesia">Micronesia</option>
                                <option value="Moldova">Moldova</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montenegro">Montenegro</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Mozambique">Mozambique</option>
                                <option value="Myanmar, {Burma}">Myanmar, {Burma}</option>
                                <option value="Namibia">Namibia</option>
                                <option value="Nauru">Nauru</option>
                                <option value="Nepal">Nepal</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nicaragua">Nicaragua</option>
                                <option value="Niger">Niger</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="Norway">Norway</option>
                                <option value="North Korea">North Korea</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Palau">Palau</option>
                                <option value="Panama">Panama</option>
                                <option value="Papua New Guinea">Papua New Guinea</option>
                                <option value="Paraguay">Paraguay</option>
                                <option value="Peru">Peru</option>
                                <option value="Philippines">Philippines</option>
                                <option value="Poland">Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Romania">Romania</option>
                                <option value="Russian Federation">Russian Federation</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="St Kitts &amp; Nevis">St Kitts &amp; Nevis</option>
                                <option value="St Lucia">St Lucia</option>
                                <option value="Saint Vincent &amp; the Grenadines">Saint Vincent &amp; the Grenadines</option>
                                <option value="Samoa">Samoa</option>
                                <option value="San Marino">San Marino</option>
                                <option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Senegal">Senegal</option>
                                <option value="Serbia">Serbia</option>
                                <option value="Seychelles">Seychelles</option>
                                <option value="Sierra Leone">Sierra Leone</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Slovakia">Slovakia</option>
                                <option value="Slovenia">Slovenia</option>
                                <option value="Solomon Islands">Solomon Islands</option>
                                <option value="Somalia">Somalia</option>
                                <option value="South Africa">South Africa</option>
                                <option value="South Korea">South Korea</option>
                                <option value="Spain">Spain</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Suriname">Suriname</option>
                                <option value="Swaziland">Swaziland</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syria">Syria</option>
                                <option value="Taiwan">Taiwan</option>
                                <option value="Tajikistan">Tajikistan</option>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Togo">Togo</option>
                                <option value="Tonga">Tonga</option>
                                <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Turkmenistan">Turkmenistan</option>
                                <option value="Tuvalu">Tuvalu</option>
                                <option value="Uganda">Uganda</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                                <option value="Uruguay">Uruguay</option>
                                <option value="Uzbekistan">Uzbekistan</option>
                                <option value="Vanuatu">Vanuatu</option>
                                <option value="Vatican City">Vatican City</option>
                                <option value="Venezuela">Venezuela</option>
                                <option value="Vietnam">Vietnam</option>
                                <option value="Yemen">Yemen</option>
                                <option value="Zambia">Zambia</option>
                                <option value="Zimbabwe">Zimbabwe</option>

                        </select><br/>
                                <span class="selectRequiredMsg">Please select an item.</span></span>
                        </td>
                </tr>
                <tr><th><label>Primary Residence in</label></th>
                		<td>
                        	<span id="spryselect6">
                        	<select name="residence_country">
                                <option value="">Country...</option>
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Antigua &amp; Deps">Antigua &amp; Deps</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bosnia Herzegovina">Bosnia Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina">Burkina</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Cape Verde">Cape Verde</option>
                                <option value="Central African Rep">Central African Rep</option>
                                <option value="Chad">Chad</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Congo {Democratic Rep}">Congo {Democratic Rep}</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic">Czech Republic</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="East Timor">East Timor</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Estonia">Estonia</option>
                                <option value="Ethiopia">Ethiopia</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Greece">Greece</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Guinea">Guinea</option>
                                <option value="Guinea-Bissau">Guinea-Bissau</option>
                                <option value="Guyana">Guyana</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="India">India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Iran">Iran</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland {Republic}">Ireland {Republic}</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Ivory Coast">Ivory Coast</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japan">Japan</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kazakhstan">Kazakhstan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kiribati">Kiribati</option>
								<option value="Kosovo">Kosovo</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                <option value="Laos">Laos</option>
                                <option value="Latvia">Latvia</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Lesotho">Lesotho</option>
                                <option value="Liberia">Liberia</option>
                                <option value="Libya">Libya</option>
                                <option value="Liechtenstein">Liechtenstein</option>
                                <option value="Lithuania">Lithuania</option>
                                <option value="Luxembourg">Luxembourg</option>
                                <option value="Macedonia">Macedonia</option>
                                <option value="Madagascar">Madagascar</option>
                                <option value="Malawi">Malawi</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Mali">Mali</option>

                                <option value="Malta">Malta</option>
                                <option value="Marshall Islands">Marshall Islands</option>
                                <option value="Mauritania">Mauritania</option>
                                <option value="Mauritius">Mauritius</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Micronesia">Micronesia</option>
                                <option value="Moldova">Moldova</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montenegro">Montenegro</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Mozambique">Mozambique</option>
                                <option value="Myanmar, {Burma}">Myanmar, {Burma}</option>
                                <option value="Namibia">Namibia</option>
                                <option value="Nauru">Nauru</option>
                                <option value="Nepal">Nepal</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nicaragua">Nicaragua</option>
                                <option value="Niger">Niger</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="Norway">Norway</option>
                                <option value="North Korea">North Korea</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Palau">Palau</option>
                                <option value="Panama">Panama</option>
                                <option value="Papua New Guinea">Papua New Guinea</option>
                                <option value="Paraguay">Paraguay</option>
                                <option value="Peru">Peru</option>
                                <option value="Philippines">Philippines</option>
                                <option value="Poland">Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Romania">Romania</option>
                                <option value="Russian Federation">Russian Federation</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="St Kitts &amp; Nevis">St Kitts &amp; Nevis</option>
                                <option value="St Lucia">St Lucia</option>
                                <option value="Saint Vincent &amp; the Grenadines">Saint Vincent &amp; the Grenadines</option>
                                <option value="Samoa">Samoa</option>
                                <option value="San Marino">San Marino</option>
                                <option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Senegal">Senegal</option>
                                <option value="Serbia">Serbia</option>
                                <option value="Seychelles">Seychelles</option>
                                <option value="Sierra Leone">Sierra Leone</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Slovakia">Slovakia</option>
                                <option value="Slovenia">Slovenia</option>
                                <option value="Solomon Islands">Solomon Islands</option>
                                <option value="Somalia">Somalia</option>
                                <option value="South Africa">South Africa</option>
                                <option value="South Korea">South Korea</option>
                                <option value="Spain">Spain</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Suriname">Suriname</option>
                                <option value="Swaziland">Swaziland</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syria">Syria</option>
                                <option value="Taiwan">Taiwan</option>
                                <option value="Tajikistan">Tajikistan</option>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Togo">Togo</option>
                                <option value="Tonga">Tonga</option>
                                <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Turkmenistan">Turkmenistan</option>
                                <option value="Tuvalu">Tuvalu</option>
                                <option value="Uganda">Uganda</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                                <option value="Uruguay">Uruguay</option>
                                <option value="Uzbekistan">Uzbekistan</option>
                                <option value="Vanuatu">Vanuatu</option>
                                <option value="Vatican City">Vatican City</option>
                                <option value="Venezuela">Venezuela</option>
                                <option value="Vietnam">Vietnam</option>
                                <option value="Yemen">Yemen</option>
                                <option value="Zambia">Zambia</option>
                                <option value="Zimbabwe">Zimbabwe</option>

                                </select><br/>
                                <span class="selectRequiredMsg">Please select an item.</span></span>
                        </td>
                </tr>
                <tr><th><label>Domicile</label></th>
                		<td>
                        	Town / Suburb / City :<br/>
               	    <span id="sprytextfield9">
                    <input type="text" name="domicile_city" maxlength="50" class="textBox1" /><br/>
                  <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span><br/>
                            State / Province /Prefecture / Canton :<br/>
                    <span id="sprytextfield10">
                    <input type="text" name="domicile_province" maxlength="50" class="textBox1" /><br/>
<span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></td>
                </tr>
                <tr><th><label>Postal/Zip Code</label></th>
                	<td><span id="sprytextfield11">
               	 	<input type="text" name="postal_code" class="textBox2" maxlength="10" /><br/>
              		<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></td></tr>
                <tr><th><label>Last 3 Digits of<br/>Current Passport Number</label></th>
                	<td><span id="sprytextfield12">
                	<input type="text" name="passport_num" class="textBox2" maxlength="3" /><br/>
              		<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></td></tr>
                <tr><th><label>Current Passport Expiry Date</label></th>
                		<td>
                        	Year 
                            <span id="spryselect7">
                      <select name="pass_exp_year">
                      			<option value="" selected="selected">Select</option>
                            	<?php
									$year = date("Y");
									for($i=($year);$i<=($year+20);$i++)
									{
										?><option value="<?php echo $i;?>"><?php echo $i;?></option><?php
									}
								?>
                            </select>
                            <span class="selectRequiredMsg">Please select an item.</span></span>
                            <br/>
                            Month 
                            <span id="spryselect8">
                      <select name="pass_exp_month">
                      			<option value="" selected="selected">Select</option>
                            	<?php
									for($i=1;$i<=12;$i++)
									{
										?><option value="<?php printf("%02d",$i); ?>"><?php printf("%02d",$i);?></option><?php
									}
								?>
                            </select>
                            <span class="selectRequiredMsg">Please select an item.</span></span>
                            <br/>
                            Day 
                            <span id="spryselect9">
                            <select name="pass_exp_day">
                            	<option value="" selected="selected">Select</option>
                            	<?php
									for($i=1;$i<=31;$i++)
									{
										?><option value="<?php printf("%02d",$i); ?>"><?php printf("%02d",$i); ?></option><?php
									}
								?>
                            </select>
                       	<span class="selectRequiredMsg">Please select an item.</span></span>
                            
                  </td>
                </tr>
                <tr><th><label>Distinguishing Features or Facts</label></th>
                	<td><span id="sprytextarea2">
                  <textarea name="distinguish_facts" cols="50" rows="10" class="textBox1"></textarea><br/>
              <span class="textareaRequiredMsg">A value is required.</span></span></td></tr>
                
                <tr><th colspan="2" class="thtitle">Identity Verification In Person</th></tr>
                <tr><th><label>Temporary Password</label></th><td><input type="text" name="temp_password" class="textBox2" maxlength="32" /></td></tr>
                <tr><th><label>Objective Question 1</label></th><td><input type="text" name="pub_q1" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Objective Answer 1</label></th><td><input type="text" name="pub_a1" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Objective Question 2</label></th><td><input type="text" name="pub_q2" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Objective Answer 2</label></th><td><input type="text" name="pub_a2" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Objective Question 3</label></th><td><input type="text" name="pub_q3" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Objective Answer 3</label></th><td><input type="text" name="pub_a3" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Subjective Question 1</label></th><td><input type="text" name="pri_q1" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Subjective Answer 1</label></th><td><input type="text" name="pri_a1" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Subjective Question 2</label></th><td><input type="text" name="pri_q2" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Subjective Answer 2</label></th><td><input type="text" name="pri_a2" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Subjective Question 3</label></th><td><input type="text" name="pri_q3" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Subjective Answer 3</label></th><td><input type="text" name="pri_a3" class="textBox1" maxlength="255" /></td></tr>
                <tr><th><label>Number Matching</label></th><td><textarea name="num_matching" class="textBox1" ></textarea></td></tr>
                
            </table>
           	<div style="clear:both; width:100%; height:30px;"></div>
             
             <div style="color:red; float:left; margin-right:50px">Create a new registrant process might take some time.<br/>Please be patient.</div><input type="submit" value="Create a new registrant"/>
    	</form>
        </fieldset>
    <?php
	}
	?>
    </div>


<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {maxChars:100});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {isRequired:false});
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "integer", {maxChars:3});
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3");
var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4");
var spryselect5 = new Spry.Widget.ValidationSelect("spryselect5");
var spryselect6 = new Spry.Widget.ValidationSelect("spryselect6");
var spryselect7 = new Spry.Widget.ValidationSelect("spryselect7");
var spryselect8 = new Spry.Widget.ValidationSelect("spryselect8");
var spryselect9 = new Spry.Widget.ValidationSelect("spryselect9");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "none", {maxChars:50});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "none", {maxChars:50, isRequired:false});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "integer", {maxChars:3});
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "email", {maxChars:70});
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9", "none", {maxChars:50});
var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "none", {maxChars:50, isRequired:false});
var sprytextfield11 = new Spry.Widget.ValidationTextField("sprytextfield11", "none", {maxChars:10});
var sprytextfield12 = new Spry.Widget.ValidationTextField("sprytextfield12", "none", {maxChars:3});
var sprytextarea2 = new Spry.Widget.ValidationTextarea("sprytextarea2");
//-->
</script>
<?php 
include("footer.php"); 
}
else
{
	$_SESSION['adminError'] = "You should log in by using your Registrant ID and Password";
	header("location:../error.php");
}
?>