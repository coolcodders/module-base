<?php
 /**
 * Cool Codders
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * php version 7.0
 *
 * @category Cool Cooders
 * @package  CoolCodders_Base
 * @author   Raju S <rajus@coolcodders.com>
 * @license  OSL 3.0
 * @link     https://www.coolcodders.com/
 */

namespace CoolCodders\Base\Plugin;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\Session;
use Closure;

class CustomerContext 
{
	const ID = "customer_id";
	const NAME = "customer_name";
	const EMAIL = "customer_email";

	/**
	 * @var Context
	 */
	protected $httpContext;

	/**
	 * @var Session
	 */
	protected $customerSession;

	public function __construct(
		Context $httpContext,
		Session $customerSession
	){
		$this->httpContext = $httpContext;
		$this->customerSession = $customerSession;
	}

	/**
	 * @param ActionInterface $subject
	 * @param callable $proceed
	 * @param RequestInterface $request
	 * 
	 * @return mixed
	 */
	public function aroundDispatch(
		ActionInterface $subject,
		Closure $proceed,
		RequestInterface $request
	) {
		$customer = $this->customerSession->getCustomer();
		if ($customer->getId()) {
			$this->httpContext->setValue(
				self::ID,
				$customer->getId(),
				false
			);

			$this->httpContext->setValue(
				self::NAME,
				$this->customerSession->getCustomer()->getName(),
				false
			);

			$this->httpContext->setValue(
				self::EMAIL,
				$this->customerSession->getCustomer()->getEmail(),
				false
			);
		}
		return $proceed($request);
	}
}