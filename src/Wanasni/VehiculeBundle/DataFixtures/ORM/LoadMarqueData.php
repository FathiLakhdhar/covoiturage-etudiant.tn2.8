<?php

namespace Wanasni\VehiculeBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Wanasni\VehiculeBundle\Entity\Marque;
use Wanasni\VehiculeBundle\Entity\Modele;

class LoadMarqueData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $Modeles_AUDI_String = ["100", "200", "50", "5000", "60", "70", "75", "80", "90", "A1", "A2", "A3", "A3 Sedan", "A3 SPORTBACK", "A4", "A4 ALLROAD", "A4 III", "A5", "A5 SPORTBACK", "A6", "A6 allroad quattro", "A6 III", "A6 IV", "A6 Quattro S Line", "A6 QUATTRO SLINE", "A6 S Line", "A7", "A8", "ALLROAD", "CABRIOLET", "COUPE", "GT", "III", "Q3", "Q5", "Q7", "QUATTRO", "R8", "RO", "RO 80", "RS3", "RS4", "RS5", "RS6", "S1", "S2", "S3", "S3 SPORTBACK", "S4", "S5", "S6", "S8", "SPORTBACK", "SQ5", "TT", "V8"];
        $Modeles_BMW_String = ["1", "1 series", "116", "118", "120", "123", "130", "135", "1502", "1600", "1602", "1800", "1802", "2 Series", "2000", "2002", "2500", "3", "3 GT", "3 series", "3,0", "3,3", "315", "316", "318", "320", "323", "324", "325", "328", "330", "335", "4 Series", "420d", "420i", "425d", "428i", "430d", "435d", "435i", "5", "5 series", "518", "520", "523", "524", "525", "528", "530", "535", "540", "545", "550", "6 series", "628", "630", "633", "635", "640D", "645", "650", "7", "7 series", "725", "728", "730", "732", "733", "735", "740", "745", "750", "760", "8 series", "840", "850", "coupe", "F800GS", "i3", "i8", "k100lt", "L7", "M135i", "M3", "M4", "M5", "M535", "M6", "M635", "MINI", "MINI COUNTRYMAN", "R 1200 GS", "R 1200RT", "R1200ST", "SERIE", "SéRIE 1", "SERIE 1 II", "Série 2 Tourer", "SéRIE 3", "SERIE 3 VI", "Serie 4", "SERIE 5", "SERIE 6", "SERIE 7", "SERIE 8", "X1", "X3", "X4", "X5", "X6", "Z1", "Z3", "Z4", "Z4 M", "Z8"];
        $Modeles_FIAT_String = ["", "124", "125p", "126", "127", "128", "130", "131", "132", "133", "238", "242", "4x4", "4X4 CROSS", "500", "500C", "500L", "500X", "600", "850", "900", "ALBEA", "ARGENTA", "Avventura", "BARCHETTA", "BRAVA", "BRAVA / BRAVO", "BRAVO", "BRAVO II", "CINQUECENTO", "COUPE", "CROMA", "CROSS", "DOBLO", "DUCATO", "Duna", "EVO", "FIORINO", "FIORINO QUBO", "FREEMONT", "Grande", "Grande PUNTO", "IDEA", "II", "LINEA", "MAREA", "MULTIPLA", "NEW DOBLÒ", "PALIO", "PANDA", "PANDA CROSS", "PININFARINA", "PUNTO", "PUNTO 2", "PUNTO EVO", "Qubo", "REGATA", "RITMO", "SCUDO", "SEDICI", "SEICENTO", "SIENA", "STILO", "STRADA", "TEMPRA", "TIPO", "TIPO Opening Edition Plus 2016", "ULYSSE", "Uno", "Weekend", "X 1/9", "X1/9"];
        $Modeles_NISSAN_String = ["100", "100 NX", "100NX", "180SX", "200", "200 SX", "200sx", "240SX", "280ZX", "300", "300 ZX", "300ZX", "350", "350 Z", "350Z", "370", "370 Z", "AD", "ALMERA", "Almera Classic", "ALMERA TINO", "Altima", "APRIO", "Armada", "ATLEON", "Avenir", "Bassara", "BLUEBIRD", "Bluebird Sylphy", "CABSTAR", "Caravan", "CEDRIC", "Cefiro", "CHERRY", "Cima", "Civilian", "Crew", "CUBE", "Datsun", "ECO", "ECO T100", "Elgrand", "EVALIA", "Expert", "Fairlady", "Frontier", "Gloria", "Grand Livina", "GT-R", "II", "INTERSTAR", "JUKE", "KING-CAB", "KUBISTAR", "L35", "Lafesta", "LARGO", "Laurel", "LEAF", "Leopard", "Liberty", "Livina Geniss", "Lucino", "March", "MAXIMA", "MAXIMA QX", "MICRA", "Mistral", "Moco", "MURANO", "NAVARA", "NOTE", "NP300", "NV200", "NV400", "NX", "Otti", "PATHFINDER", "Pathfinder Aramada", "PATROL", "PICK-UP", "Pickup", "PIXO", "Platina", "PRAIRIE", "Presage", "Presea", "President", "PRIMASTAR", "PRIMERA", "Pulsar", "Qashqai", "Qashqai+2", "QUEST", "QX", "R'nessa", "Rasheen", "Rogue", "Safari", "Sentra", "SERENA", "SILVIA", "Skyline", "Stagea", "STANZA", "SUNNY", "SX", "T100", "Teana", "TERRANO", "TERRANO II", "TIIDA", "Tiida latio", "TINO", "Titan", "TRADE", "Tsuru", "URVAN", "VANETTE", "Versa", "Wingroad", "X-TRAIL", "Xterra", "Z", "ZX"];
        $Modeles_MERCEDES_String = ["190", "200", "206", "207", "208", "209", "210", "220", "230", "240", "250", "250 CDI GLE 4Matic", "250 D", "260", "280", "300", "306", "307", "308", "309", "310", "320", "350", "400", "406", "407", "408", "409", "410", "420", "450", "500", "507", "508", "560", "600", "608", "609", "611", "614", "709", "711", "714", "809", "814", "A", "A 140", "ACTROS", "B", "C", "C 63", "C250", "C270", "CITAN", "CL", "CLA", "CLA 180", "CLA 220", "CLASSE", "CLASSE A", "CLASSE B", "CLASSE C", "Classe C 270", "CLASSE C COUPE SPORT", "CLASSE C III", "CLASSE CL", "CLASSE CLC", "CLASSE CLK", "CLASSE CLS", "CLASSE E", "Classe E Coupé", "Classe E250 CDI BE Avantgarde", "CLASSE G", "CLASSE GL", "CLASSE GLK", "CLASSE M", "CLASSE R", "CLASSE S", "Classe S500", "CLASSE SL", "CLASSE SLK", "CLASSE SLS", "CLASSE V", "CLC", "CLK", "CLK 270 cdi", "CLS", "E", "E 250 Turbo Avantgarde", "E290", "G", "GL", "GLA 200 CDI", "GLC 220", "Glé Coupé", "GLK", "GLK 250", "KAFER", "M", "MB100", "ML 320", "O309", "PLUS", "R", "S", "SL", "SLK", "SLS", "SPRINTER", "V", "VANEO", "VARIO", "VARIO PLUS", "VIANO", "VITO"];
        $Modeles_MITSUBISHI_String = ["3000", "3000 GT", "Airtrek", "Aspire", "ASX", "Bravo", "CANTER", "CARISMA", "Challenger", "Chariot", "COLT", "Debonair", "Delica", "DIAMANTE", "Dingo", "Dion", "ECLIPSE", "EK", "Emeraude", "Endeavor", "ESTATE", "Eterna", "FTO", "Fuso Canter", "GALANT", "GRANDIS", "GTO", "i", "I-MIEV", "L 200", "L200", "L300", "L400", "LANCER", "Lancer Cedia", "Lancer Evolution", "Legnum", "Libero", "Magna", "Minica", "Mirage", "MONTERO", "Montero Sport", "OUTLANDER", "Outlander XL", "PAJERO", "Pajero Dakar", "Pajero IO", "Pajero Junior", "Pajero Mini", "Pajero Pinin", "Pajero Sport", "Precis", "Proudia", "Raider", "RUNNER", "RVR", "SAPPORO", "SHOGUN PININ", "Sigma", "SPACE", "Space Gear", "SPACE RUNNER", "SPACE STAR", "SPACE WAGON", "STAR", "Toppo", "Town Box", "Town Box Wide", "Triton", "WAGON"];
        $Modeles_RENAULT_String = ["10", "11", "14", "18", "19", "21", "25", "30", "4", "4 CV", "406 COUPE", "4CV", "5", "8", "9", "AVANTIME", "B110", "B120", "B70", "B80", "B90", "CAPTUR", "CARAVELLE", "CHEROKEE", "CJ7", "CLEO", "CLIO", "Clio Campus", "CLIO ESTATE", "Clio GRANDTOUR", "CLIO II", "CLIO III", "CLIO III ESTATE", "CLIO IV", "CLIO IV ESTATE", "Clio Sport", "CLIO SPORTOUR", "Clio V6", "DAUPHINE", "DAUPHINOIS", "DOKKER", "DUSTER", "ESPACE", "ESPACE IV", "ESPACE V", "ESTAFETTE", "ESTATE", "EXPRESS", "FLORIDE", "FLUENCE", "FREGATE", "FUEGO", "G", "GRAND", "GRAND ESPACE", "GRAND ESPACE IV", "GRAND KANGOO", "GRAND MODUS", "GRAND SCENIC", "GRANDTOUR", "II", "III", "IV", "JEEP", "JEEP CJ7", "JUVAQUATRE", "Kadjar", "KANGOO", "KANGOO EXPRESS", "KANGOO EXPRESS II", "KANGOO II", "KOLEOS", "KWID", "LAGUNA", "LAGUNA COUPE", "Laguna Grand Tour", "LAGUNA II", "LAGUNA II ESTATE", "LAGUNA III", "LAGUNA III ESTATE", "LAGUNA NEVADA", "LATITUDE", "LODGY", "LOGAN", "LOGAN II", "Magnum", "MASCOTT", "MASTER", "MASTER III", "MAXITY", "MEGANE", "MEGANE COUPE", "Megane Coupe-Cabriolet", "MEGANE II", "MEGANE II ESTATE", "MEGANE III", "MEGANE III ESTATE", "Megane III RS", "MÉGANE IV GT", "MESSENGER", "MIDLINER M160", "Midlum", "MODUS", "NEVADA", "ONDINE", "PRAIRIE", "Premium", "Pulse", "QASHQAI", "R10", "R11", "R12", "R14", "R15", "R16", "R17", "R18", "R19", "R20", "R21", "R21 NEVADA", "R25", "R30", "R4", "R5", "R6", "R8", "R9", "Rapid", "RODEO", "SAFRANE", "SANDERO", "Sandero Stepway", "SATIS", "SAVANE", "SAVIEM", "SCALA", "SCENIC", "SCENIC II", "SCENIC III", "SCENIC RX4", "SPIDER", "Sport Spyder", "SUPERCINQ", "Symbol", "Talisman", "THALIA", "TRAFIC", "TWINGO", "TWINGO II", "Twingo III", "TWIZY", "VEL", "VEL SATIS", "WIND", "WRANGLER", "XBA", "ZOE"];
        $Modeles_TOYOTA_String = ["4", "4 Runner", "4-RUNNER", "ACE", "Allex", "Allion", "Alphard", "Altezza", "Aristo", "Aurion", "AURIS", "AURIS HYBRID", "AVALON", "AVANZA", "AVENSIS", "AVENSIS VERSO", "AYGO", "Bandeirante", "bB", "Blade", "Brevis", "Caldina", "Cami", "CAMRY", "Camry Solara", "Carib", "CARINA", "CARINA E", "Carina ED", "CARINA II", "Cavalier", "CELICA", "Celsior", "Century", "Chaser", "Coaster", "COMMUTER", "COROLLA", "COROLLA 2", "Corolla Altis", "Corolla Ceres", "Corolla Fielder", "Corolla II", "Corolla Levin", "Corolla Rumion", "Corolla Runx", "Corolla Spacio", "COROLLA VERSO", "CORONA", "Corona Premio", "Corsa", "CRESSIDA", "Cresta", "CROWN", "Crown Athlete", "Crown Majesta", "Crown Royal", "CRUISER", "Curren", "Cynos", "Duet", "DYNA", "E", "Echo", "Emina Estima", "Estima", "Estima Hybrid", "Estima Lucida", "Etios", "Etios Sedan", "F", "FJ Cruiser", "Fortuner", "Funcargo", "FUNCRUISER", "Gaia", "Grand hiace", "Granvia", "GT86", "Harrier", "HI ACE", "HI LUX", "HI-ACE", "HI-LUX", "Hiace", "Highlander", "Hilux", "Hilux Surf", "II", "Innova", "Ipsum", "IQ", "Isis", "Ist", "Kluger", "LAND CRUISER", "Land Cruiser Prado", "Land Cruiser Prado 150", "LANDCRUISER", "LANDCRUISER 100", "LANDCRUISER 80", "LANDCRUISER 90", "LEXUS", "LITE", "LITE ACE", "LIVA", "Mark II", "Mark X", "Mark X Zio", "MASTERACE", "Matrix", "MODELE", "MODELE F", "MR", "MR ROADSTER", "MR2", "MRS", "Nadia", "Noah", "Opa", "Origin", "PASEO", "Passo", "Passo Sette", "PICNIC", "Platz", "Porte", "PRADO", "Premio", "PREVIA", "PRIUS", "PRIUS+", "Proace +", "Probox", "Progres", "Pronard", "QUALIS", "Ractis", "Raum", "RAV", "RAV 4", "RAV4", "RAV4 II", "Regius", "Regius ACE", "RUNNER", "Rush", "Scepter", "Scion", "Sequoia", "Sera", "Sienna", "Sienta", "Soarer", "Solara", "Sparky", "Sprinter", "Sprinter Marino", "STARLET", "Succeed", "SUPRA", "Tacoma", "TERCEL", "Town Ace", "TRD MR2", "Tundra", "URBAN", "URBAN CRUISER", "VENTURY", "Venza", "Verossa", "VERSO", "VERSO-S", "Vios", "Vista", "Vitz", "Voltz", "Voxy", "Will Cypha", "Will VS", "Windom", "Wish", "YARIS", "YARIS HYBRID", "YARIS II", "YARIS III", "Yaris Verso"];

        $arr_Marques = array(
            'AUDI' => $Modeles_AUDI_String,
            'BMW' => $Modeles_BMW_String,
            'FIAT' => $Modeles_FIAT_String,
            'MERCEDES' => $Modeles_MERCEDES_String,
            'MITSUBISHI' => $Modeles_MITSUBISHI_String,
            'NISSAN' => $Modeles_NISSAN_String,
            'RENAULT' => $Modeles_RENAULT_String,
            'TOYOTA' => $Modeles_TOYOTA_String,
        );


        foreach($arr_Marques as $key=> $value){

            $marque=new Marque();
            $marque->setId($key);
            $marque->setCarBrand($key);


            foreach($value as $mdl){
                $model=new Modele();
                $model->setCarModel($mdl);
                $model->setMarque($marque);
                $marque->addModele($model);
            }

            $manager->persist($marque);
        }

        $manager->flush();


    }

}
