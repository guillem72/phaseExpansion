<?php
namespace glluch\phaseExpansion;
require_once __DIR__."/classes/FindTerms.php";

$taxo0=  unserialize(\file_get_contents(__DIR__."/resources/tests/phpTaxonomy.ser"));
$expansion =new FindTerms();
$taxo=$expansion->setTermsFromTaxo($taxo0);
\file_put_contents("taxo0.ser", \serialize($taxo));
\var_dump($taxo);
$text_doc="123 Logic devices and Engineering management and Computational and artificial intelligence  and B-ISDN and Logic devices and B-ISDN";
$text_doc0="B.2. Component Integration. 
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

$expansion->setDoc($text_doc0);
$positions=$expansion->find();
//var_dump($positions);
//$expansion->deleteFP();
//\file_put_contents("sol0.ser", \serialize($positions));