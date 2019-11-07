<?php
$initialClasses = get_declared_classes();

$magentoInc->setAdminHtml();

Class Clean
{

    /**
     * @var \Gibson\Customer\Helper\Data
     */
    private $customerHelper;
    /**
     * @var \Gibson\Dotmailer\Model\Email\CreateCustomer
     */
    private $createCustomer;

    function __construct(\Gibson\Customer\Helper\Data $customerHelper,
\Gibson\Dotmailer\Model\Email\CreateCustomer $createCustomer)
    {

        $this->customerHelper = $customerHelper;
        $this->createCustomer = $createCustomer;
    }

    public function clean()
    {
        $email = '581699_gibcust_glendale@nextra.com.au';
        return $this->customerHelper->trimCustomerEmail($email);
    }
    public function test()
    {
        $this->createCustomer->createCustomerViaImport();
        return ;
//        $email = $this->createCustomer->getRandomEmail();
        $email = '592472_gcust_testcustomer_71258@gmail.com';
//        $email = '592472_gcust_testcustomer@gmail.com';
        $clean = $this->customerHelper->trimCustomerEmail($email);
        return compact('email','clean');
    }
}


ZActionDetect::showOutput(end($initialClasses),$magentoInc);
