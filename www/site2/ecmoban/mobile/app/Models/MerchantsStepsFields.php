<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class MerchantsStepsFields extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'merchants_steps_fields';
	protected $primaryKey = 'fid';
	public $timestamps = false;
	protected $fillable = array('user_id', 'agreement', 'steps_site', 'site_process', 'contactName', 'contactPhone', 'contactEmail', 'organization_code', 'organization_fileImg', 'companyName', 'business_license_id', 'legal_person', 'personalNo', 'legal_person_fileImg', 'license_comp_adress', 'license_adress', 'establish_date', 'business_term', 'busines_scope', 'license_fileImg', 'company_located', 'company_adress', 'company_contactTel', 'company_tentactr', 'company_phone', 'taxpayer_id', 'taxs_type', 'taxs_num', 'tax_fileImg', 'status_tax_fileImg', 'company_name', 'account_number', 'bank_name', 'linked_bank_number', 'linked_bank_address', 'linked_bank_fileImg', 'company_type', 'company_website', 'company_sale', 'shop_seller_have_experience', 'shop_website', 'shop_employee_num', 'shop_sale_num', 'shop_average_price', 'shop_warehouse_condition', 'shop_warehouse_address', 'shop_delicery_company', 'shop_erp_type', 'shop_operating_company', 'shop_buy_ecmoban_store', 'shop_buy_delivery', 'preVendorId', 'preVendorId_fileImg', 'shop_vertical', 'registered_capital', 'contactXinbie', 'is_distribution');
	protected $guarded = array();

	public function getUserId()
	{
		return $this->user_id;
	}

	public function getAgreement()
	{
		return $this->agreement;
	}

	public function getStepsSite()
	{
		return $this->steps_site;
	}

	public function getSiteProcess()
	{
		return $this->site_process;
	}

	public function getContactName()
	{
		return $this->contactName;
	}

	public function getContactPhone()
	{
		return $this->contactPhone;
	}

	public function getContactEmail()
	{
		return $this->contactEmail;
	}

	public function getOrganizationCode()
	{
		return $this->organization_code;
	}

	public function getOrganizationFileImg()
	{
		return $this->organization_fileImg;
	}

	public function getBusinessLicenseId()
	{
		return $this->business_license_id;
	}

	public function getLegalPerson()
	{
		return $this->legal_person;
	}

	public function getPersonalNo()
	{
		return $this->personalNo;
	}

	public function getLegalPersonFileImg()
	{
		return $this->legal_person_fileImg;
	}

	public function getLicenseCompAdress()
	{
		return $this->license_comp_adress;
	}

	public function getLicenseAdress()
	{
		return $this->license_adress;
	}

	public function getEstablishDate()
	{
		return $this->establish_date;
	}

	public function getBusinessTerm()
	{
		return $this->business_term;
	}

	public function getBusinesScope()
	{
		return $this->busines_scope;
	}

	public function getLicenseFileImg()
	{
		return $this->license_fileImg;
	}

	public function getCompanyLocated()
	{
		return $this->company_located;
	}

	public function getCompanyAdress()
	{
		return $this->company_adress;
	}

	public function getCompanyContactTel()
	{
		return $this->company_contactTel;
	}

	public function getCompanyTentactr()
	{
		return $this->company_tentactr;
	}

	public function getCompanyPhone()
	{
		return $this->company_phone;
	}

	public function getTaxpayerId()
	{
		return $this->taxpayer_id;
	}

	public function getTaxsType()
	{
		return $this->taxs_type;
	}

	public function getTaxsNum()
	{
		return $this->taxs_num;
	}

	public function getTaxFileImg()
	{
		return $this->tax_fileImg;
	}

	public function getStatusTaxFileImg()
	{
		return $this->status_tax_fileImg;
	}

	public function getCompanyName()
	{
		return $this->company_name;
	}

	public function getAccountNumber()
	{
		return $this->account_number;
	}

	public function getBankName()
	{
		return $this->bank_name;
	}

	public function getLinkedBankNumber()
	{
		return $this->linked_bank_number;
	}

	public function getLinkedBankAddress()
	{
		return $this->linked_bank_address;
	}

	public function getLinkedBankFileImg()
	{
		return $this->linked_bank_fileImg;
	}

	public function getCompanyType()
	{
		return $this->company_type;
	}

	public function getCompanyWebsite()
	{
		return $this->company_website;
	}

	public function getCompanySale()
	{
		return $this->company_sale;
	}

	public function getShopSellerHaveExperience()
	{
		return $this->shop_seller_have_experience;
	}

	public function getShopWebsite()
	{
		return $this->shop_website;
	}

	public function getShopEmployeeNum()
	{
		return $this->shop_employee_num;
	}

	public function getShopSaleNum()
	{
		return $this->shop_sale_num;
	}

	public function getShopAveragePrice()
	{
		return $this->shop_average_price;
	}

	public function getShopWarehouseCondition()
	{
		return $this->shop_warehouse_condition;
	}

	public function getShopWarehouseAddress()
	{
		return $this->shop_warehouse_address;
	}

	public function getShopDeliceryCompany()
	{
		return $this->shop_delicery_company;
	}

	public function getShopErpType()
	{
		return $this->shop_erp_type;
	}

	public function getShopOperatingCompany()
	{
		return $this->shop_operating_company;
	}

	public function getShopBuyEcmobanStore()
	{
		return $this->shop_buy_ecmoban_store;
	}

	public function getShopBuyDelivery()
	{
		return $this->shop_buy_delivery;
	}

	public function getPreVendorId()
	{
		return $this->preVendorId;
	}

	public function getPreVendorIdFileImg()
	{
		return $this->preVendorId_fileImg;
	}

	public function getShopVertical()
	{
		return $this->shop_vertical;
	}

	public function getRegisteredCapital()
	{
		return $this->registered_capital;
	}

	public function getContactXinbie()
	{
		return $this->contactXinbie;
	}

	public function getIsDistribution()
	{
		return $this->is_distribution;
	}

	public function setUserId($value)
	{
		$this->user_id = $value;
		return $this;
	}

	public function setAgreement($value)
	{
		$this->agreement = $value;
		return $this;
	}

	public function setStepsSite($value)
	{
		$this->steps_site = $value;
		return $this;
	}

	public function setSiteProcess($value)
	{
		$this->site_process = $value;
		return $this;
	}

	public function setContactName($value)
	{
		$this->contactName = $value;
		return $this;
	}

	public function setContactPhone($value)
	{
		$this->contactPhone = $value;
		return $this;
	}

	public function setContactEmail($value)
	{
		$this->contactEmail = $value;
		return $this;
	}

	public function setOrganizationCode($value)
	{
		$this->organization_code = $value;
		return $this;
	}

	public function setOrganizationFileImg($value)
	{
		$this->organization_fileImg = $value;
		return $this;
	}

	public function setBusinessLicenseId($value)
	{
		$this->business_license_id = $value;
		return $this;
	}

	public function setLegalPerson($value)
	{
		$this->legal_person = $value;
		return $this;
	}

	public function setPersonalNo($value)
	{
		$this->personalNo = $value;
		return $this;
	}

	public function setLegalPersonFileImg($value)
	{
		$this->legal_person_fileImg = $value;
		return $this;
	}

	public function setLicenseCompAdress($value)
	{
		$this->license_comp_adress = $value;
		return $this;
	}

	public function setLicenseAdress($value)
	{
		$this->license_adress = $value;
		return $this;
	}

	public function setEstablishDate($value)
	{
		$this->establish_date = $value;
		return $this;
	}

	public function setBusinessTerm($value)
	{
		$this->business_term = $value;
		return $this;
	}

	public function setBusinesScope($value)
	{
		$this->busines_scope = $value;
		return $this;
	}

	public function setLicenseFileImg($value)
	{
		$this->license_fileImg = $value;
		return $this;
	}

	public function setCompanyLocated($value)
	{
		$this->company_located = $value;
		return $this;
	}

	public function setCompanyAdress($value)
	{
		$this->company_adress = $value;
		return $this;
	}

	public function setCompanyContactTel($value)
	{
		$this->company_contactTel = $value;
		return $this;
	}

	public function setCompanyTentactr($value)
	{
		$this->company_tentactr = $value;
		return $this;
	}

	public function setCompanyPhone($value)
	{
		$this->company_phone = $value;
		return $this;
	}

	public function setTaxpayerId($value)
	{
		$this->taxpayer_id = $value;
		return $this;
	}

	public function setTaxsType($value)
	{
		$this->taxs_type = $value;
		return $this;
	}

	public function setTaxsNum($value)
	{
		$this->taxs_num = $value;
		return $this;
	}

	public function setTaxFileImg($value)
	{
		$this->tax_fileImg = $value;
		return $this;
	}

	public function setStatusTaxFileImg($value)
	{
		$this->status_tax_fileImg = $value;
		return $this;
	}

	public function setCompanyName($value)
	{
		$this->company_name = $value;
		return $this;
	}

	public function setAccountNumber($value)
	{
		$this->account_number = $value;
		return $this;
	}

	public function setBankName($value)
	{
		$this->bank_name = $value;
		return $this;
	}

	public function setLinkedBankNumber($value)
	{
		$this->linked_bank_number = $value;
		return $this;
	}

	public function setLinkedBankAddress($value)
	{
		$this->linked_bank_address = $value;
		return $this;
	}

	public function setLinkedBankFileImg($value)
	{
		$this->linked_bank_fileImg = $value;
		return $this;
	}

	public function setCompanyType($value)
	{
		$this->company_type = $value;
		return $this;
	}

	public function setCompanyWebsite($value)
	{
		$this->company_website = $value;
		return $this;
	}

	public function setCompanySale($value)
	{
		$this->company_sale = $value;
		return $this;
	}

	public function setShopSellerHaveExperience($value)
	{
		$this->shop_seller_have_experience = $value;
		return $this;
	}

	public function setShopWebsite($value)
	{
		$this->shop_website = $value;
		return $this;
	}

	public function setShopEmployeeNum($value)
	{
		$this->shop_employee_num = $value;
		return $this;
	}

	public function setShopSaleNum($value)
	{
		$this->shop_sale_num = $value;
		return $this;
	}

	public function setShopAveragePrice($value)
	{
		$this->shop_average_price = $value;
		return $this;
	}

	public function setShopWarehouseCondition($value)
	{
		$this->shop_warehouse_condition = $value;
		return $this;
	}

	public function setShopWarehouseAddress($value)
	{
		$this->shop_warehouse_address = $value;
		return $this;
	}

	public function setShopDeliceryCompany($value)
	{
		$this->shop_delicery_company = $value;
		return $this;
	}

	public function setShopErpType($value)
	{
		$this->shop_erp_type = $value;
		return $this;
	}

	public function setShopOperatingCompany($value)
	{
		$this->shop_operating_company = $value;
		return $this;
	}

	public function setShopBuyEcmobanStore($value)
	{
		$this->shop_buy_ecmoban_store = $value;
		return $this;
	}

	public function setShopBuyDelivery($value)
	{
		$this->shop_buy_delivery = $value;
		return $this;
	}

	public function setPreVendorId($value)
	{
		$this->preVendorId = $value;
		return $this;
	}

	public function setPreVendorIdFileImg($value)
	{
		$this->preVendorId_fileImg = $value;
		return $this;
	}

	public function setShopVertical($value)
	{
		$this->shop_vertical = $value;
		return $this;
	}

	public function setRegisteredCapital($value)
	{
		$this->registered_capital = $value;
		return $this;
	}

	public function setContactXinbie($value)
	{
		$this->contactXinbie = $value;
		return $this;
	}

	public function setIsDistribution($value)
	{
		$this->is_distribution = $value;
		return $this;
	}
}

?>
