<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;


class ExtadmControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		\Aimeos\Aimeos\Base::getAimeos(); // initialize autoloader
		$config = \Aimeos\Aimeos\Base::getConfig();

		$context = new \Aimeos\MShop\Context\Item\Standard();
		$context->setView( new \Aimeos\MW\View\Standard() );
		$context->setConfig( $config );

		$this->object = $this->getAccessibleMock( 'Aimeos\\Aimeos\\Controller\\ExtadmController', ['getContextBackend'] );
		$this->view = $this->getMock( 'TYPO3\\CMS\\Fluid\\View\\TemplateView', array(), array(), '', false );

		$uriBuilder = $this->getMockBuilder( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder' )->getMock();
		$request = $this->getMockBuilder( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Request' )->getMock();
		$response = $this->getMockBuilder( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Response' )
			->setMethods( ['getHeaders'] )
			->getMock();

		$this->object->expects( $this->any() )->method( 'getContextBackend' )->will( $this->returnValue( $context ) );


		$uriBuilder->setRequest( $request );

		if( method_exists( $response, 'setRequest' ) ) {
			$response->setRequest( $request );
		}

		$this->object->_set( 'uriBuilder', $uriBuilder );
		$this->object->_set( 'response', $response );
		$this->object->_set( 'request', $request );
		$this->object->_set( 'view', $this->view );

		$this->object->_call( 'initializeAction' );
	}


	public function tearDown()
	{
		unset( $this->object );
	}


	/**
	 * @test
	 */
	public function indexAction()
	{
		$this->view->expects( $this->atLeastOnce() )->method( 'assign' );

		$this->object->indexAction();
	}


	/**
	 * @test
	 */
	public function doAction()
	{
		$this->view->expects( $this->once() )->method( 'assign' )
			->with( $this->equalTo( 'response' ), $this->stringContains( '{' ) );

		$this->object->doAction();
	}


	/**
	 * @test
	 */
	public function fileAction()
	{
		$this->assertInternalType( 'string', $this->object->fileAction() );
	}
}