<?php
namespace glluch\phaseExpansion;
require_once __DIR__.'/../../classes/FindTerms.php';
/**
 * Test FindeTerms class
 *
 * @author Guillem LLuch Moll <guillem72@gmail.com>
 */
class FindTermsTest extends \PHPUnit_Framework_TestCase {
   protected $doc;
   protected $termsFile;
   protected $solutionFile;
   protected $taxoFile;
   
   function __construct() {
       $this->doc = "B.2. Component Integration. 
    Integrates hardware, software or sub system components into an existing or a new system. Complies with established processes and procedures such as, configuration management and package maintenance. Takes into account the compatibility of existing and new modules to ensure system integrity, system interoperability and information security. Verifies and tests system capacity and performance and documentation of successful integration.
Proficiency Levels
Proficiency Level 2 - Acts systematically to identify compatibility of software and hardware specifications. Documents all activities during installation and records deviations and remedial activities.
Proficiency Level 3 - Accounts for own and others actions in the integration process. Complies with appropriate standards and change control procedures to maintain integrity of the overall system functionality and reliability.
Proficiency Level 4 - Exploits wide ranging specialist knowledge to create a process for the entire integration cycle, including the establishment of internal standards of practice. Provides leadership to marshal and assign resources for programmes of integration.
Knowledge Examples
K1 old, existing and new hardware components/ software programs/ modules
K2 the impact that system integration has on existing system/ organisation
K3 interfacing techniques between modules, systems and components
K4 integration testing techniques
K5 development tools (e.g. development environment, management, source code access/revision control)
K6 best practice design techniques
Skills Examples
S1 measure system performance before, during and after system integration
S2 document and record activities, problems and related repair activities
S3 match customers' needs with existing products
S4 verify that integrated systems capabilities and efficiency match specifications
S5 secure/ back-up data to ensure integrity during system integration";
       $this->termsFile =__DIR__."/../../resources/tests/phpTaxonomy.ser";
       $this->solutionFile = __DIR__."/../../resources/tests/sol0.ser";
        $this->taxoFile = __DIR__."/../../resources/tests/taxo0.ser";
   }

   public function testDoc0(){
       $terms=\unserialize(\file_get_contents($this->termsFile));
       $sol=\unserialize(\file_get_contents($this->solutionFile));
       $solTaxo=\unserialize(\file_get_contents($this->taxoFile));
       $expansion =new FindTerms();
       $taxo=$expansion->setTermsFromTaxo($terms);
       $expansion->setDoc($this->doc);
       $positions=$expansion->find();
       
       $this->assertEquals($positions,$sol);
       $this->assertEquals($taxo,$solTaxo);
   }
   
   
}
