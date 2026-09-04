<?php

$raw = <<<DATA
1W     $450     GA     DAN     FinancialPlannerGeorgia.com
2M     *****     WA     DAN     WashingtonSecurityGuard.com
2M     $250     NH     DAN     NewHampshireFinancialAdvisor.com
2M     *****     MN     DAN     MinneapolisDrugRehab.com
10M   $350     TX      NL       HoustonWarehousing.com
5M     $500                NL       BlueSkySatellite.com
3M     $150     TN     DAN     NashvilleProductionCompany.com
2W     $100     FL      DAN     ShedsFlorida.com
2M     $100    CAN    DAN     MississaugaPsychologist.com
2D      $300     FL       DAN     BoatToursFlorida.com
1M     $150     FL      DAN     MiamiLaserSpa.com
1M     $499     FL      DAN     CentralFloridaCFP.com
3W     $650     NV     DAN     NevadaCFP.com
2W     $200     CO     DAN     ElkHuntsColorado.com
2W     $100     MT    DAN     MontanaWebsiteDesign.com
***      $1299             GC       CaissonCapital.com
9M     $275     NV     DAN     LasVegasCFP.com
1M     $250     SC      DAN     MyrtleBeachLuxuryLiving.com
1D      $250     OH     DAN     CincinnatiWindowTint.com
1W     $500     FL      DAN     SouthFloridaBoatRepair.com
3D      $100     OR     DAN     OregonAsphaltPaving.com
10M   $1000   UT     DAN     ProvoFlooring.com
5M     $699     CA      AN       LosAngelesAsphalt.com
1W     $299     TX     DAN     TexasEscapeRooms.com
1W     $300     CA     DAN     SoCalFinancialAdvisors.com
3W     $500     IN      DAN     IndianaIPLaw.com
6M     $650     UT       AN      UtahLiquorStore.com
1M     $250     CO     DAN     DenverWeddingMusic.com
1D      $799     OR      AN       PortlandSurrogacy.com
2M     $150     AZ     DAN     TucsonTint.com
1M     $100     NY     EPIK     MentalHealthBrooklyn.com
***      $500     CA     ****      SanDiegoGarageDoor.com
1M     $200     IL       DAN     SteelBuildingsIllinois.com
9M     $100     CT     DAN     ConnecticutFlyFishing.com
4M     $400     AZ     DAN     HVACInArizona.com
4D      $250     FL      DAN     OrlandoCounselingCenter.com
US      $100     OR     DAN     PortlandInsuranceAgency.com
1M     $350    AUS    DAN     AustraliaMSP.com
3W     $200   CAN     DAN     OntarioITServices.com
1W     $100     TX     DAN     TexasBoatTours.com
1M     $499     TX      NL       CorpusChristiInjuryAttorney.com
1M     $225     TX      NL       DFWHVACCompany.com
4D      $100     FL      DAN     OrlandoBestImmigrationLawFirm.com
US      $100     FL      DAN     OrlandoBestImmigrationAttorney.com
US      $100     FL      DAN     OrlandoBestImmigrationLawyer.com
US      $100     FL      DAN     MiamiBestImmigrationLawyer.com
US      $100     FL      DAN     MiamiBestImmigrationLawFirm.com
US      $100     FL      DAN     MiamiBestImmigrationAttorney.com
3W     $100     NY     DAN     WestchesterCleaning.com
1M     $200     ##     DAN      LAManufacturedHomes.com
4M     $125     UT     DAN     UTWaterproofing.com
4D      $199     MN    DAN     VideoProductionsMinneapolis.com
1M     $750     TN     DAN     ElderLawNashville.com
2M     $200     NY     DAN     BodySculptingNY.com
3M     $100     TX     DAN     DigitalAgencyTexas.com
4M     $500               EPIK    Watch-Buyers.com
2M     $599     KY     AN       LouisvilleHouseBuyers.com
5D      $399     CA     DAN     MedicalSpaLosAngeles.com
6M     $100     FL      DAN     MiamiFlyingClub.com
8M     $250     OK     DAN     TulsaConcreteContractors.com
2M     $250     NC     DAN     AshevilleLuxuryRealty.com
1W     $500     MO     DAN     StLouisDogTraining.com
***      $399     FL       NL       MiamiInsuranceLawyers.com
***      $300                 NL      PinnacleRealtor.com
***      $250     TX      NL       FriscoFamilyLawAttorney.com
***      $200     CA      NL       UsedCarsModesto.com
***      €499                NL       SpringbokCasino.net
2W     $200     NY     DAN     DogTrainingManhattan.com
***      $149                NL       EstatePlanningAndProbateLawyer.com
***      $100     CA      NL      LosAngelesSolarPower.com
***      $100     AZ      NL      PhoenixHomeValuation.com
***      $150     MA     NL      TheBostonRealty.com
***      $233     CA      NL      SanFranciscoEndodontics.com
***      $233     NV      NL      NevadaSolarPanel.com
***      $200     MO     NL      StLouisCountyRoofing.com
***      $333     ##      NL       KCRemodelers.com
***      $133     NV     NL        LasVegasPetGrooming.com
***      $211                NL       ButlerLimousine.com
***      $100     WA     NL       SpokanePressureWash.com
***      $205     NV     NL       LasVegasCommercialBroker.com
***      $151               NL        AmadorSolar.com
***      $441               NL        ProbateAndEstatePlanningAttorney.com
***      $1499             NL        Cannalys.com
***      $180     AZ      NL       ArizonaPowerWash.com
***      $100     TX       NL       HoustonBrowLashStudio.com
***      $111     IL        NL       ChicagoPianoSchool.com
***      $199     NY      NL       NewYorkCityPropertyManager.com
11M   $150    CAN   DAN     GeneralContractorCalgary.com
3M     $200     CA     DAN     CAAutoCredit.com
2M     $200     NY     DAN     NewYorkCremationServices.com
5M     $100    AUS    DAN     MelbourneWeddingDjs.com
1M     $250     MI      DAN     MichiganHealthCareInsurance.com
1M     $150     AZ     NL       ScottsdaleAppraisers.com
2M     $300     OR     NL       PortlandHomeRentals.com
***     $100     KY      NL       LouisvilleDogTrainer.com
***     $288                NL       EaglePropertySolutions.com
2M     $273     TX     EPIK     HoustonAffordableDentist.com
EUR   $150               DAN     OldTownTrees.com
1W     $150     FL     EPIK     TampaFloridaCharters.com
8M     $100     MO     DAN     StLouisNewbornPhotographer.com
7M     $250     GA     DAN     MarriageCounselingGeorgia.com
1M     $150     OR     DAN     OregonFinancialAdvisor.com
1M     $100    CAN   DAN     CalgaryFamilyLawFirm.com
6M     $100     MI     DAN      PhotoboothMichigan.com
1W     $300     CT     DAN     ConnecticutRoofRepair.com
5M     $425     FL     DAN      AtlanticBeachRealtor.com
3D      $300     IL      DAN       ChicagoComputerSupport.com
1M     $200     FL     DAN       TampaFLCPA.com
1M     $399     FL      DAN      BocaRatonKitchenAndBath.com
***      *****     VA      NL        VirginiaBeachMarketingAgency.com
1W     $850                DAN     SpearFishingShop.com
5M     $399     NC      GD        FinancialAdvisorRaleigh.com
1M     $100     WV     DAN     SeniorCareWestVirginia.com
6M     $100     GA     DAN     FishingGuidesGeorgia.com
2M     $200     FL     DAN     YachtChartersTampa.com
8M     $150     TX     DAN     CustomJewelerAustin.com
3D      $250     OH     DAN     ManagedITCleveland.com
3W     $400     VT     DAN     MentalHealthVermont.com
EUR   $200     CA     DAN     LosAngelesITSecurity.com
3W     $350     TN     DAN     PlasticSurgeonNashville.com
1M     $200     TX     DAN     DallasManufacturedHomes.com
1M     $199     MI     DAN     MichiganPoleBuildings.com
1M     $250     FL     DAN     SouthFloridaMSP.com
6M     $100     CO     DAN     ColoradoBoudoirPhotographer.com
11M   $350    CAN   DAN     ChilliwackRealEstateAgent.com
1M     $150     CA     DAN     CaliforniaModularHome.com
1D      $650     KY     DAN     ManagedITKentucky.com
US     $100     FL      DAN     FLBestImmigrationAttorney.com
US     $100     FL      DAN     FLBestImmigrationLawyer.com
US     $100     FL      DAN     MiamiFLImmigrationLawFirm.com
US     $100     FL      DAN     OrlandoFLImmigrationLawFirm.com
2D     $250     CA     DAN     DowntownSanDiegoCondos.com
1M     $899     ##     DAN     OCFinancialAdvisors.com
***     $300     FL      DAN     OrlandoDroneServices.com
***     $150     TX     DAN     HoustonLandClearing.com
***     $199     TX     DAN     DFWPressureWashing.com
***     $599     LA     AN        LouisianaDisabilityAttorney.com
***     $299     CA     AN       SanFranciscoVets.com
***     $350     AR     AN       ArkansasDivorceAttorney.com
***     $299     OR     DAN     PortlandInsuranceAgents.com
***     $499     OH     DAN     OhioSoberLiving.com
***     $499     TX     DAN     AustinPlumbingService.com
***     $350     TX     DAN     LubbockSeo.com
***     $599    AUS    DAN     SydneyForklift.com
***     $499     MD     DAN     BaltimoreMedSpa.com
***     $399     TX     DAN     TexasPrivateDetective.com
***     $299     PA     DAN     PACriminalDefenseLawyer.com
6M     $300     CA     DAN     TemeculaWeddingPhotographer.com
1W     $599     FL     DAN     FloridaITSecurity.com
2W     $499     TX     DAN     TexasITSecurity.com
7M     $650     MO     AN      HomesForSaleSTL.com
2D      $650     GA     DAN     GeorgiaSLP.com
1W     $200     CA     DAN     BjjLosAngeles.com
1D      $400     SC     DAN     FishingChartersMyrtleBeach.com
9M     $100     GA     DAN     FlightSchoolGeorgia.com
EUR    $100     FL     DAN     FLBestImmigrationLawFirm.com
3W     $150     OR     DAN     OregonManagedIT.com
4M     $200     ID      DAN      BoiseHomeRemodeling.com
3W     $100     FL     DAN     BrowardTutoring.com
1W     $235    CAN   DAN    ManagedITVancouver.com
1D      $100     OR    EPIK     CommercialRealEstateOregon.com
4D      $1488   NY    DAN     BrooklynSoftwareDevelopment.com
2W     $200     FL     DAN     DestinFloridaCharters.com
1M     $250     TX     DAN     WebHostingHouston.com
1M     $100     TX     DAN     EstatePlanningSanAntonio.com
1M     $110     NJ     DAN     NJCloudServices.com
4M     $250     UT     DAN     UTBoatRentals.com
8M     $499     TN     DAN     DentalImplantsNashville.com
2W     $300     AZ     DAN     GarageDoorRepairArizona.com
9M     $150     OR     DAN     PortlandEstateAttorney.com
9M     $199     AZ     DAN     CustomHomeBuilderPhx.com
2M     $175     UT     DAN     UtahDesignStudios.com
1M     $150     TN     DAN     ConcreteChattanooga.com
2M     $100     FL      SH       JacksonvilleConcealedCarry.com
3M     $100     ##     SH        PasadenaWebDesigner.com
US      $100     GA     DAN     AtlantaBestChiropractic.com
1M     $300     NJ     DAN     NorthJerseyAttorney.com
***      *****      IL     GC        ChicagoMobileBillboards.com
***      *****               GC       MobileBillboardTrucks.com
***      *****               GC       MobileBillboardTruck.com
***      $200     NY     GC       NYBillboards.com
3W     $200               EPIK    MobileBillboardAd.com
2W     $250     TX     DAN     HoustonElderLawFirm.com
3D      $300     TX     DAN     TexasSLP.com
9M     $400     ##     DAN     OntarioCriminalLaw.com
2W     $250     NY     DAN     NYCEntertainmentLawFirm.com
***      $250     OK     DAN     AirDuctCleaningTulsa.com
***      $399     ##     DAN     ArlingtonWealthManagement.com
***      $100     PA     DAN     PhiladelphiaCourier.com
1M     $250     MD     DAN     InjuryLawyerColumbia.com
2M     $100     TN     DAN     ChattanoogaContractors.com
2W     $150     VA     DAN     WeldingVirginia.com
1Y      $400     FL     EPIK     SantaRosaPersonalInjuryLawyer.com
US     $400    CAN   DAN     ManagedITCalgary.com
3M     $100     FL     DAN     FloridaConcealedHandgun.com
1Y      $200     FL     DAN     OrlandoBankruptcyLawFirm.com
1M     $400     VA     DAN     VirginiaPoleBarns.com
1M     $399     TX     DAN     TexasFlooringContractor.com
5M     $300   CAN    DAN     DrugRehabOntario.com
US      $200     CA     DAN     SacramentoMarriageCounselor.com
1W     $399     CA     DAN     SacramentoMarriageCounseling.com
1M     $399     CT     DAN     ConnecticutRoofInspection.com
10M   $130     IL       NL       ChicagoBestFurniture.com
4D      $350     CA     DAN     FresnoElderLaw.com
8M     $200     TN     DAN     RoofingClarksville.com
1M     $150     NY     DAN     NewYorkEstateBrokers.com
2W     $99      TX      NL       DFWDrainCleaning.com
1M     $400     FL      NL       HillsboroughMovers.com
1M     $145*   CO      NL      RoofingServicesDenver.com
4D      $300     KY     DAN     KentuckyCounseling.com
1D      $850     OH     AN       OhioCDLTraining.com
1W     $1288  TX      DAN      AustinMSP.com
2D      $200     MI      DAN      ManagedITDetroit.com
1M     $400     TX     DAN     FinancialPlannerDFW.com
4D      $177     WY     DAN     WyomingCDLTraining.com
4M     $300     MA     DAN     MassachusettsWeddingDjs.com
9M     $199     WI      DAN     WisconsinSteelBuildings.com
3W     $299     ##     DAN     OrangeCountyElderLaw.com
***      $5995              NL      Immensive.com
4D      $350     CA     DAN     CADrivingSchool.com
4M     $150     CO     DAN     DenverHomeOrganizer.com
5M     $500     FL      DAN     KeywestSpearFishing.com
4M     $300     FL      DAN     MiamiWebDesigns.com
10M   $150     ID      DAN     BoiseAirductCleaning.com
1M     $400     CO     AN       ColoradoCommercialPainting.com
2W     $450     ##     DAN     ManagedITOrangeCounty.com
3D      $200     NY    DAN     WestchesterVocalCoach.com
2D      $500     TX     NL       TXHomeAppraisal.com
2M     $225     KY     NL       LouisvilleConcealedCarry.com
3W     $200     CA     NL       FresnoDrugRehab.com
1M     $350     PA     NL        PittsburghMalpracticeLawyer.com
***      $1999             NL        Nexovi.com
3M     $150     ##     DAN     ArlingtonHomeRemodeling.com
1M     $600               DAN     ManufacturedHomeBroker.com
1M     $500   CAN    DAN     CalgaryManagedIT.com
3W     $750     GA     DAN     GAIPLaw.com
7M     $150     LA     DAN     VOIPLouisiana.com
4D      $250     FL     DAN     MiamiBeachImmigrationLawyer.com
5M     $199     ##     DAN     CarolinaElderCare.com
2M     $200     FL      DAN     HypnosisSouthFlorida.com
***     $199    CAN     NL      TorontoHeadshotPhotography.com
3M     $200                            PlankwoodFlooring.com
2M     $100     TX      NL       TexasEliteHomes.com
9M     $300     TN      NL       AutoSalesNashville.com
1W     $400     FL      DAN     MiamiNewbornPhotographer.com
2M     $200     IL       DAN     ChicagoFireProtection.com
1W     $250     FL      DAN     SouthFloridaCyberSecurity.com
1W     $200     CA     DAN     SanDiegoManagedITServices.com
1M     $350     MN     DAN     MinnesotaShredding.com
10M   $430    CAN    AN      InjuryLawyerBrampton.com
7M     $350     MT     AN      MontanaInteriorDesigner.com
***     $4802              AN      TheBlackLotus.com
9M     $150     MI     DAN     MITowing.com
1Y      $250     MA    DAN     MassachusettsPhotoBooth.com
1W     $150     IL      DAN     IllinoisManagedIT.com
2M     $100     NV    DAN     VegasGeneralContractor.com
1M     $399     PA     DAN     PhiladelphiaMSP.com
2W     $199     TX     DAN     DivorceLawyerDFW.com
3M     $100     CA     DAN     ChulaVistaDUIAttorney.com
4M     $250     FL     DAN     FloridaRhinoplastySpecialist.com
11M   $499     SC     DAN     CriminalLawSouthCarolina.com
3W     $325     GA     DAN     AtlantaBestChiropractor.com
1D      $800     MD     DAN     MarylandCDLTraining.com
1M     $300     IL      DAN       ILFireProtection.com
8M     $399     AZ     AN        TucsonVideoProductions.com
2M     $200     MD     DAN     MarylandDogBite.com
6M     $250     CA     DAN      RealtyHollywood.com
1W     $300     NY     DAN     VocalCoachNewYork.com
1W     $200     LA     DAN     LouisianaDigitalAgency.com
1Y      $200     TX     DAN      WindowTintingDFW.com
1W     $599     TX     DAN     ConsultingAgencyTexas.com
1M     $199     KY     DAN     LouisvilleMSP.com
6M     $200    CAN   DAN      MarkhamRealtor.com
3M     $100                NL       GateFixers.com
1Y      $200                NL        BuyMed.co
9M     $500                NP        DogHotel.co
2W     $150     OK      NL        FoundationRepairOKC.com
2W     $100     OK     DAN      TulsaOklahomaChiropractor.com
1M     $125     FL      DAN      EstatePlanningOcala.com
2M     $150     AZ     DAN      ArizonaLuxuryHomeRentals.com
6M     $200     CAN   DAN     OntarioMicroneedling.com
1W     $750     FL      DAN     FloridaCDLTraining.com
3W     $400     UT      DAN     UtahTaxCPA.com
2M     $125     CAN    DAN    OakvilleEstatePlanning.com
3M     $100     GA      DAN     ImmigrationLawyersAtlanta.com
2M     $125     AZ      DAN     PrescottWeddingPhotographer.com
3Y      $7500              NP       SwissFinancialAdvisers.com
***     $300     CO      GC       ColoradoWindowReplacement.com
***     $100     CO       GC      WindowReplacementColorado.com
***     $300     CAN    GC       OttawaCoworking.com
***     $400      OR      GC       PortlandDentRepair.com
1W     $299     OH     DAN     WindowTintingCincinnati.com
6M     $4700              NL       CloneRobotics.com
1M     $500     NY     DAN     AlbanyInjuryLaw.com
9M     $200     MI      DAN     BoudoirPhotographyMichigan.com
1D      $275     MI      DAN     MetroDetroitPhotography.com
3W     $175     LA     DAN     LouisianaWeddingPhotography.com
2W     $200     MA    DAN     MassachusettsDrivingSchool.com
7M     $150     FL      DAN     TampaBayEstateAttorney.com
3M     $995               AN        RehabMetaVerse.com
3M     $150     FL     DAN     PhotoBoothSouthFlorida.com
2D      $250     TX     DAN     DigitalAgencyDallas.com
1M     $100     TX     DAN     TXFingerPrinting.com
2W     $299     CAN  EPIK     BarrieDrywall.com
1M     $200               DAN     RacialDiscriminationAttorney.com
4M     $800     TN     DAN     SolarEnergyTennessee.com
1W     $250     KY     DAN     KentuckySprayFoamInsulation.com
2W     $350     WI     DAN     WisconsinManagedIT.com
10M    $100    ##      NL      ColumbusMetalRecycling.com
***      $150     CA     NL       OaklandLandscapeDesign.com
6M     $100     CA     DAN     LosAngelesPreciousMetals.com
6M     $250     FL     DAN     JacksonvilleJewelers.com
6M     $200     TX     DAN     HoustonBridalPhotography.com
1M     $200     AZ     DAN     PhoenixPoolServices.com
3M     $200     TN     DAN     JohnsonCityRealtor.com
3M     $200     ID      DAN      IdahoInteriorDesigner.com
1M     $250     UT     DAN     PrivateInvestigatorsUtah.com
1Y      $100     NY     DAN     NassauCountyChiropractic.com
6M     $399     AZ     DAN     ArizonaWelding.com
2M     $100     FL      DAN     FloristHialeah.com
1M     $100     CA     DAN     BakersfieldRenovations.com
3W     $200     ##     DAN     LASelfDefense.com
2M     $225     CO     AN       WeddingVenueDenver.com
1Y      $499     TX      AN       LewisvilleMovers.com
3M     $100     GA     DAN     InteriorDesignsAtlanta.com
3W     $100     TX     DAN     JunkRemovalFrisco.com
5D      $650     AK     DAN     AlaskaMentalHealth.com
5M     $450     MA     NL       SolarEnergyMassachusetts.com
3M     $100     GA     DAN     EstateAttorneyAtlanta.com
2W     $450     TN     DAN     NashvilleManagedIT.com
5D      $599     NC     DAN     SeniorCareNorthCarolina.com
3W     $200     ME     DAN     CoworkingMaine.com
3M     $200     NJ     DAN     MarketingAgencyNewJersey.com
1W     $325     ##     DAN     ArlingtonMSP.com
1Y      $200     NC     DAN     LawFirmGreensboro.com
2W     $250     NY     DAN     NewYorkRefinishing.com
1M     $250     WA     DAN     SeattleDronePhotography.com
7M     $2335              DAN     LegionTech.com
1Y      $150     ##     DAN     PhotoBoothOrangeCounty.com
5M     $100     FL      DAN     RealEstateLawSouthFlorida.com
1M     $200     NH     DAN     InjuryAttorneysNH.com
DATA;

// base (cleaned, lowercase) => city|keyword|state|country
$data = [
    'financialplannergeorgia' => 'Georgia|financial planner|Georgia|United States',
    'washingtonsecurityguard' => 'Washington|security guard|Washington|United States',
    'newhampshirefinancialadvisor' => 'New Hampshire|financial advisor|New Hampshire|United States',
    'minneapolisdrugrehab' => 'Minneapolis|drug rehab|Minnesota|United States',
    'houstonwarehousing' => 'Houston|warehousing|Texas|United States',
    'blueskysatellite' => '|satellite||',
    'nashvilleproductioncompany' => 'Nashville|production company|Tennessee|United States',
    'shedsflorida' => 'Florida|sheds|Florida|United States',
    'mississaugapsychologist' => 'Mississauga|psychologist|Ontario|Canada',
    'boattoursflorida' => 'Florida|boat tours|Florida|United States',
    'miamilaserspa' => 'Miami|laser spa|Florida|United States',
    'centralfloridacfp' => 'Central Florida|CFP|Florida|United States',
    'nevadacfp' => 'Nevada|CFP|Nevada|United States',
    'elkhuntscolorado' => 'Colorado|elk hunts|Colorado|United States',
    'montanawebsitedesign' => 'Montana|website design|Montana|United States',
    'caissoncapital' => '|capital||',
    'lasvegascfp' => 'Las Vegas|CFP|Nevada|United States',
    'myrtlebeachluxuryliving' => 'Myrtle Beach|luxury living|South Carolina|United States',
    'cincinnatiwindowtint' => 'Cincinnati|window tint|Ohio|United States',
    'southfloridaboatrepair' => 'South Florida|boat repair|Florida|United States',
    'oregonasphaltpaving' => 'Oregon|asphalt paving|Oregon|United States',
    'provoflooring' => 'Provo|flooring|Utah|United States',
    'losangelesasphalt' => 'Los Angeles|asphalt|California|United States',
    'texasescaperooms' => 'Texas|escape rooms|Texas|United States',
    'socalfinancialadvisors' => 'Southern California|financial advisors|California|United States',
    'indianaiplaw' => 'Indiana|IP law|Indiana|United States',
    'utahliquorstore' => 'Utah|liquor store|Utah|United States',
    'denverweddingmusic' => 'Denver|wedding music|Colorado|United States',
    'portlandsurrogacy' => 'Portland|surrogacy|Oregon|United States',
    'tucsontint' => 'Tucson|tint|Arizona|United States',
    'mentalhealthbrooklyn' => 'Brooklyn|mental health|New York|United States',
    'sandiegogaragedoor' => 'San Diego|garage door|California|United States',
    'steelbuildingsillinois' => 'Illinois|steel buildings|Illinois|United States',
    'connecticutflyfishing' => 'Connecticut|fly fishing|Connecticut|United States',
    'hvacinarizona' => 'Arizona|HVAC|Arizona|United States',
    'orlandocounselingcenter' => 'Orlando|counseling center|Florida|United States',
    'portlandinsuranceagency' => 'Portland|insurance agency|Oregon|United States',
    'australiamsp' => 'Australia|MSP|Victoria|Australia',
    'ontarioitservices' => 'Ontario|IT services|Ontario|Canada',
    'texasboattours' => 'Texas|boat tours|Texas|United States',
    'corpuschristiinjuryattorney' => 'Corpus Christi|injury attorney|Texas|United States',
    'dfwhvaccompany' => 'DFW|HVAC company|Texas|United States',
    'orlandobestimmigrationlawfirm' => 'Orlando|immigration law firm|Florida|United States',
    'orlandobestimmigrationattorney' => 'Orlando|immigration attorney|Florida|United States',
    'orlandobestimmigrationlawyer' => 'Orlando|immigration lawyer|Florida|United States',
    'miamibestimmigrationlawyer' => 'Miami|immigration lawyer|Florida|United States',
    'miamibestimmigrationlawfirm' => 'Miami|immigration law firm|Florida|United States',
    'miamibestimmigrationattorney' => 'Miami|immigration attorney|Florida|United States',
    'westchestercleaning' => 'Westchester|cleaning|New York|United States',
    'lamanufacturedhomes' => 'Los Angeles|manufactured homes|California|United States',
    'utwaterproofing' => 'Utah|waterproofing|Utah|United States',
    'videoproductionsminneapolis' => 'Minneapolis|video productions|Minnesota|United States',
    'elderlawnashville' => 'Nashville|elder law|Tennessee|United States',
    'bodysculptingny' => 'New York|body sculpting|New York|United States',
    'digitalagencytexas' => 'Texas|digital agency|Texas|United States',
    'watchbuyers' => '|watch buyers||',
    'louisvillehousebuyers' => 'Louisville|house buyers|Kentucky|United States',
    'medicalspalosangeles' => 'Los Angeles|medical spa|California|United States',
    'miamiflyingclub' => 'Miami|flying club|Florida|United States',
    'tulsaconcretecontractors' => 'Tulsa|concrete contractors|Oklahoma|United States',
    'ashevilleluxuryrealty' => 'Asheville|luxury realty|North Carolina|United States',
    'stlouisdogtraining' => 'St. Louis|dog training|Missouri|United States',
    'miamiinsurancelawyers' => 'Miami|insurance lawyers|Florida|United States',
    'pinnaclerealtor' => '|realtor||',
    'friscofamilylawattorney' => 'Frisco|family law attorney|Texas|United States',
    'usedcarsmodesto' => 'Modesto|used cars|California|United States',
    'springbokcasino' => '|casino||',
    'dogtrainingmanhattan' => 'Manhattan|dog training|New York|United States',
    'estateplanningandprobatelawyer' => '|estate planning and probate lawyer||',
    'losangelessolarpower' => 'Los Angeles|solar power|California|United States',
    'phoenixhomevaluation' => 'Phoenix|home valuation|Arizona|United States',
    'thebostonrealty' => 'Boston|realty|Massachusetts|United States',
    'sanfranciscoendodontics' => 'San Francisco|endodontics|California|United States',
    'nevadasolarpanel' => 'Nevada|solar panel|Nevada|United States',
    'stlouiscountylroofing' => 'St. Louis County|roofing|Missouri|United States',
    'kcremodelers' => 'Kansas City|remodelers|Missouri|United States',
    'lasvegaspetgrooming' => 'Las Vegas|pet grooming|Nevada|United States',
    'butlerlimousine' => '|limousine||',
    'spokanepressurewash' => 'Spokane|pressure wash|Washington|United States',
    'lasvegascommercialbroker' => 'Las Vegas|commercial broker|Nevada|United States',
    'amadorsolar' => '|solar||',
    'probateandestateplanningattorney' => '|probate and estate planning attorney||',
    'cannalys' => '|cannalys||',
    'arizonapowerwash' => 'Arizona|power wash|Arizona|United States',
    'houstonbrowlashstudio' => 'Houston|brow lash studio|Texas|United States',
    'chicagopianoschool' => 'Chicago|piano school|Illinois|United States',
    'newyorkcitypropertymanager' => 'New York City|property manager|New York|United States',
    'generalcontractorcalgary' => 'Calgary|general contractor|Alberta|Canada',
    'caautocredit' => 'California|auto credit|California|United States',
    'newyorkcremationservices' => 'New York|cremation services|New York|United States',
    'melbourneweddingdjs' => 'Melbourne|wedding DJs|Victoria|Australia',
    'michiganhealthcareinsurance' => 'Michigan|health care insurance|Michigan|United States',
    'scottsdaleappraisers' => 'Scottsdale|appraisers|Arizona|United States',
    'portlandhomerentals' => 'Portland|home rentals|Oregon|United States',
    'louisvilledogtrainer' => 'Louisville|dog trainer|Kentucky|United States',
    'eaglepropertysolutions' => '|property solutions||',
    'houstonaffordabledentist' => 'Houston|affordable dentist|Texas|United States',
    'oldtowntrees' => '|trees||',
    'tampafloridacharters' => 'Tampa|charters|Florida|United States',
    'stlouisnewbornphotographer' => 'St. Louis|newborn photographer|Missouri|United States',
    'marriagecounselinggeorgia' => 'Georgia|marriage counseling|Georgia|United States',
    'oregonfinancialadvisor' => 'Oregon|financial advisor|Oregon|United States',
    'calgaryfamilylawfirm' => 'Calgary|family law firm|Alberta|Canada',
    'photoboothmichigan' => 'Michigan|photobooth|Michigan|United States',
    'connecticutroofrepair' => 'Connecticut|roof repair|Connecticut|United States',
    'atlanticbeachrealtor' => 'Atlantic Beach|realtor|Florida|United States',
    'chicagocomputersupport' => 'Chicago|computer support|Illinois|United States',
    'tampaflcpa' => 'Tampa|CPA|Florida|United States',
    'bocaratonkitchenandbath' => 'Boca Raton|kitchen and bath|Florida|United States',
    'virginiabeachmarketingagency' => 'Virginia Beach|marketing agency|Virginia|United States',
    'spearfishingshop' => '|spear fishing shop||',
    'financialadvisorraleigh' => 'Raleigh|financial advisor|North Carolina|United States',
    'seniorcarewestvirginia' => 'West Virginia|senior care|West Virginia|United States',
    'fishingguidesgeorgia' => 'Georgia|fishing guides|Georgia|United States',
    'yachtcharterstampa' => 'Tampa|yacht charters|Florida|United States',
    'customjeweleraustin' => 'Austin|custom jeweler|Texas|United States',
    'manageditcleveland' => 'Cleveland|managed IT|Ohio|United States',
    'mentalhealthvermont' => 'Vermont|mental health|Vermont|United States',
    'losangelesitsecurity' => 'Los Angeles|IT security|California|United States',
    'plasticsurgeonnashville' => 'Nashville|plastic surgeon|Tennessee|United States',
    'dallasmanufacturedhomes' => 'Dallas|manufactured homes|Texas|United States',
    'michiganpolebuildings' => 'Michigan|pole buildings|Michigan|United States',
    'southfloridamsp' => 'South Florida|MSP|Florida|United States',
    'coloradoboudoirphotographer' => 'Colorado|boudoir photographer|Colorado|United States',
    'chilliwackrealestateagent' => 'Chilliwack|real estate agent|British Columbia|Canada',
    'californiamodularhome' => 'California|modular home|California|United States',
    'manageditkentucky' => 'Kentucky|managed IT|Kentucky|United States',
    'flbestimmigrationattorney' => 'Florida|immigration attorney|Florida|United States',
    'flbestimmigrationlawyer' => 'Florida|immigration lawyer|Florida|United States',
    'miamiflimmigrationlawfirm' => 'Miami|immigration law firm|Florida|United States',
    'orlandoflimmigrationlawfirm' => 'Orlando|immigration law firm|Florida|United States',
    'downtownsandiegocondos' => 'San Diego|condos|California|United States',
    'ocfinancialadvisors' => 'Orange County|financial advisors|California|United States',
    'orlandodroneservices' => 'Orlando|drone services|Florida|United States',
    'houstonlandclearing' => 'Houston|land clearing|Texas|United States',
    'dfwpressurewashing' => 'DFW|pressure washing|Texas|United States',
    'louisianadisabilityattorney' => 'Louisiana|disability attorney|Louisiana|United States',
    'sanfranciscovets' => 'San Francisco|vets|California|United States',
    'arkansasdivorceattorney' => 'Arkansas|divorce attorney|Arkansas|United States',
    'portlandinsuranceagents' => 'Portland|insurance agents|Oregon|United States',
    'ohiosoberliving' => 'Ohio|sober living|Ohio|United States',
    'austinplumbingservice' => 'Austin|plumbing service|Texas|United States',
    'lubbockseo' => 'Lubbock|SEO|Texas|United States',
    'sydneyforklift' => 'Sydney|forklift|New South Wales|Australia',
    'baltimoremedspa' => 'Baltimore|med spa|Maryland|United States',
    'texasprivatedetective' => 'Texas|private detective|Texas|United States',
    'pacriminaldefenselawyer' => 'Pennsylvania|criminal defense lawyer|Pennsylvania|United States',
    'temeculaweddingphotographer' => 'Temecula|wedding photographer|California|United States',
    'floridaitsecurity' => 'Florida|IT security|Florida|United States',
    'texasitsecurity' => 'Texas|IT security|Texas|United States',
    'homesforsalestl' => 'St. Louis|homes for sale|Missouri|United States',
    'georgiaslp' => 'Georgia|SLP|Georgia|United States',
    'bjjlosangeles' => 'Los Angeles|BJJ|California|United States',
    'fishingchartersmyrtlebeach' => 'Myrtle Beach|fishing charters|South Carolina|United States',
    'flightschoolgeorgia' => 'Georgia|flight school|Georgia|United States',
    'flbestimmigrationlawfirm' => 'Florida|immigration law firm|Florida|United States',
    'oregonmanagedit' => 'Oregon|managed IT|Oregon|United States',
    'boisehomeremodeling' => 'Boise|home remodeling|Idaho|United States',
    'browardtutoring' => 'Broward|tutoring|Florida|United States',
    'manageditvancouver' => 'Vancouver|managed IT|British Columbia|Canada',
    'commercialrealestateoregon' => 'Oregon|commercial real estate|Oregon|United States',
    'brooklynsoftwaredevelopment' => 'Brooklyn|software development|New York|United States',
    'destinfloridacharters' => 'Destin|charters|Florida|United States',
    'webhostinghouston' => 'Houston|web hosting|Texas|United States',
    'estateplanningsanantonio' => 'San Antonio|estate planning|Texas|United States',
    'njcloudservices' => 'New Jersey|cloud services|New Jersey|United States',
    'utboatrentals' => 'Utah|boat rentals|Utah|United States',
    'dentalimplantsnashville' => 'Nashville|dental implants|Tennessee|United States',
    'garagedoorrepairarizona' => 'Arizona|garage door repair|Arizona|United States',
    'portlandestateattorney' => 'Portland|estate attorney|Oregon|United States',
    'customhomebuilderphx' => 'Phoenix|custom home builder|Arizona|United States',
    'utahdesignstudios' => 'Utah|design studios|Utah|United States',
    'concretechattanooga' => 'Chattanooga|concrete|Tennessee|United States',
    'jacksonvilleconcealedcarry' => 'Jacksonville|concealed carry|Florida|United States',
    'pasadenawebdesigner' => 'Pasadena|web designer|California|United States',
    'atlantabestchiropractic' => 'Atlanta|chiropractic|Georgia|United States',
    'northjerseyattorney' => 'North Jersey|attorney|New Jersey|United States',
    'chicagomobilebillboards' => 'Chicago|mobile billboards|Illinois|United States',
    'mobilebillboardtrucks' => '|mobile billboard trucks||',
    'mobilebillboardtruck' => '|mobile billboard truck||',
    'nybillboards' => 'New York|billboards|New York|United States',
    'mobilebillboardad' => '|mobile billboard ad||',
    'houstonelderlawfirm' => 'Houston|elder law firm|Texas|United States',
    'texasslp' => 'Texas|SLP|Texas|United States',
    'ontariocriminallaw' => 'Ontario|criminal law|Ontario|Canada',
    'nycentertainmentlawfirm' => 'New York City|entertainment law firm|New York|United States',
    'airductcleaningtulsa' => 'Tulsa|air duct cleaning|Oklahoma|United States',
    'arlingtonwealthmanagement' => 'Arlington|wealth management|Texas|United States',
    'philadelphiacourier' => 'Philadelphia|courier|Pennsylvania|United States',
    'injurylawyercolumbia' => 'Columbia|injury lawyer|Maryland|United States',
    'chattanoogacontractors' => 'Chattanooga|contractors|Tennessee|United States',
    'weldingvirginia' => 'Virginia|welding|Virginia|United States',
    'santarosapersonalinjurylawyer' => 'Santa Rosa|personal injury lawyer|Florida|United States',
    'manageditcalgary' => 'Calgary|managed IT|Alberta|Canada',
    'floridaconcealedhandgun' => 'Florida|concealed handgun|Florida|United States',
    'orlandobankruptcylawfirm' => 'Orlando|bankruptcy law firm|Florida|United States',
    'virginiapolebarns' => 'Virginia|pole barns|Virginia|United States',
    'texasflooringcontractor' => 'Texas|flooring contractor|Texas|United States',
    'drugrehabontario' => 'Ontario|drug rehab|Ontario|Canada',
    'sacramentomarriagecounselor' => 'Sacramento|marriage counselor|California|United States',
    'sacramentomarriagecounseling' => 'Sacramento|marriage counseling|California|United States',
    'connecticutroofinspection' => 'Connecticut|roof inspection|Connecticut|United States',
    'chicagobestfurniture' => 'Chicago|furniture|Illinois|United States',
    'fresnoelderlaw' => 'Fresno|elder law|California|United States',
    'roofingclarksville' => 'Clarksville|roofing|Tennessee|United States',
    'newyorkestatebrokers' => 'New York|estate brokers|New York|United States',
    'dfwdraincleaning' => 'DFW|drain cleaning|Texas|United States',
    'hillsboroughmovers' => 'Hillsborough|movers|Florida|United States',
    'roofingservicesdenver' => 'Denver|roofing services|Colorado|United States',
    'kentuckycounseling' => 'Kentucky|counseling|Kentucky|United States',
    'ohiocdltraining' => 'Ohio|CDL training|Ohio|United States',
    'austinmsp' => 'Austin|MSP|Texas|United States',
    'manageditdetroit' => 'Detroit|managed IT|Michigan|United States',
    'financialplannerdfw' => 'DFW|financial planner|Texas|United States',
    'wyomingcdltraining' => 'Wyoming|CDL training|Wyoming|United States',
    'massachusettsweddingdjs' => 'Massachusetts|wedding DJs|Massachusetts|United States',
    'wisconsinsteelbuildings' => 'Wisconsin|steel buildings|Wisconsin|United States',
    'orangecountyelderlaw' => 'Orange County|elder law|California|United States',
    'immensive' => '|immensive||',
    'cadrivingschool' => 'California|driving school|California|United States',
    'denverhomeorganizer' => 'Denver|home organizer|Colorado|United States',
    'keywestspearfishing' => 'Key West|spear fishing|Florida|United States',
    'miamiwebdesigns' => 'Miami|web designs|Florida|United States',
    'boiseairductcleaning' => 'Boise|air duct cleaning|Idaho|United States',
    'coloradocommercialpainting' => 'Colorado|commercial painting|Colorado|United States',
    'manageditorangecounty' => 'Orange County|managed IT|California|United States',
    'westchestervocalcoach' => 'Westchester|vocal coach|New York|United States',
    'txhomeappraisal' => 'Texas|home appraisal|Texas|United States',
    'louisvilleconcealedcarry' => 'Louisville|concealed carry|Kentucky|United States',
    'fresnodrugrehab' => 'Fresno|drug rehab|California|United States',
    'pittsburghmalpracticelawyer' => 'Pittsburgh|malpractice lawyer|Pennsylvania|United States',
    'nexovi' => '|nexovi||',
    'arlingtonhomeremodeling' => 'Arlington|home remodeling|Texas|United States',
    'manufacturedhomebroker' => '|manufactured home broker||',
    'calgarymanagedit' => 'Calgary|managed IT|Alberta|Canada',
    'gaiplaw' => 'Georgia|IP law|Georgia|United States',
    'voiplouisiana' => 'Louisiana|VOIP|Louisiana|United States',
    'miamibeachimmigrationlawyer' => 'Miami Beach|immigration lawyer|Florida|United States',
    'carolinaeldercare' => 'Carolina|elder care|Carolinas|United States',
    'hypnosissouthflorida' => 'South Florida|hypnosis|Florida|United States',
    'torontoheadshotphotography' => 'Toronto|headshot photography|Ontario|Canada',
    'plankwoodflooring' => '|wood flooring||',
    'texaselitehomes' => 'Texas|elite homes|Texas|United States',
    'autosalesnashville' => 'Nashville|auto sales|Tennessee|United States',
    'miaminewbornphotographer' => 'Miami|newborn photographer|Florida|United States',
    'chicagofireprotection' => 'Chicago|fire protection|Illinois|United States',
    'southfloridacybersecurity' => 'South Florida|cyber security|Florida|United States',
    'sandiegomanageditservices' => 'San Diego|managed IT services|California|United States',
    'minnesotashredding' => 'Minnesota|shredding|Minnesota|United States',
    'injurylawyerbrampton' => 'Brampton|injury lawyer|Ontario|Canada',
    'montanainteriordesigner' => 'Montana|interior designer|Montana|United States',
    'theblacklotus' => '|black lotus||',
    'mitowing' => 'Michigan|towing|Michigan|United States',
    'massachusettsphotobooth' => 'Massachusetts|photo booth|Massachusetts|United States',
    'illinoismanagedit' => 'Illinois|managed IT|Illinois|United States',
    'vegasgeneralcontractor' => 'Las Vegas|general contractor|Nevada|United States',
    'philadelphiamsp' => 'Philadelphia|MSP|Pennsylvania|United States',
    'divorcelawyerdfw' => 'DFW|divorce lawyer|Texas|United States',
    'chulavistaduiattorney' => 'Chula Vista|DUI attorney|California|United States',
    'floridarhinoplastyspecialist' => 'Florida|rhinoplasty specialist|Florida|United States',
    'criminallawsouthcarolina' => 'South Carolina|criminal law|South Carolina|United States',
    'atlantabestchiropractor' => 'Atlanta|chiropractor|Georgia|United States',
    'marylandcdltraining' => 'Maryland|CDL training|Maryland|United States',
    'ilfireprotection' => 'Illinois|fire protection|Illinois|United States',
    'tucsonvideoproductions' => 'Tucson|video productions|Arizona|United States',
    'marylanddogbite' => 'Maryland|dog bite|Maryland|United States',
    'realtyhollywood' => 'Hollywood|realty|California|United States',
    'vocalcoachnewyork' => 'New York|vocal coach|New York|United States',
    'louisianadigitalagency' => 'Louisiana|digital agency|Louisiana|United States',
    'windowtintingdfw' => 'DFW|window tinting|Texas|United States',
    'consultingagencytexas' => 'Texas|consulting agency|Texas|United States',
    'louisvillemsp' => 'Louisville|MSP|Kentucky|United States',
    'markhamrealtor' => 'Markham|realtor|Ontario|Canada',
    'gatefixers' => '|gate fixers||',
    'buymed' => '|buy med||',
    'doghotel' => '|dog hotel||',
    'foundationrepairokc' => 'Oklahoma City|foundation repair|Oklahoma|United States',
    'tulsaoklahomachiroprpractor' => 'Tulsa|chiropractor|Oklahoma|United States',
    'estateplanningocala' => 'Ocala|estate planning|Florida|United States',
    'arizonaluxuryhomerentals' => 'Arizona|luxury home rentals|Arizona|United States',
    'ontariomicroneedling' => 'Ontario|microneedling|Ontario|Canada',
    'floridacdltraining' => 'Florida|CDL training|Florida|United States',
    'utahtaxcpa' => 'Utah|tax CPA|Utah|United States',
    'oakvilleestateplanning' => 'Oakville|estate planning|Ontario|Canada',
    'immigrationlawyersatlanta' => 'Atlanta|immigration lawyers|Georgia|United States',
    'prescottweddingphotographer' => 'Prescott|wedding photographer|Arizona|United States',
    'swissfinancialadvisers' => '|financial advisers||Switzerland',
    'coloradowindowreplacement' => 'Colorado|window replacement|Colorado|United States',
    'windowreplacementcolorado' => 'Colorado|window replacement|Colorado|United States',
    'ottawacoworking' => 'Ottawa|coworking|Ontario|Canada',
    'portlanddentrepair' => 'Portland|dent repair|Oregon|United States',
    'windowtintingcincinnati' => 'Cincinnati|window tinting|Ohio|United States',
    'clonerobotics' => '|robotics||',
    'albanyinjurylaw' => 'Albany|injury law|New York|United States',
    'boudoirphotographymichigan' => 'Michigan|boudoir photography|Michigan|United States',
    'metrodetroitphotography' => 'Detroit|photography|Michigan|United States',
    'louisianaweddingphotography' => 'Louisiana|wedding photography|Louisiana|United States',
    'massachusettsdrivingschool' => 'Massachusetts|driving school|Massachusetts|United States',
    'tampabayestateattorney' => 'Tampa Bay|estate attorney|Florida|United States',
    'rehabmetaverse' => '|rehab metaverse||',
    'photoboothsouthflorida' => 'South Florida|photo booth|Florida|United States',
    'digitalagencydallas' => 'Dallas|digital agency|Texas|United States',
    'txfingerprinting' => 'Texas|fingerprinting|Texas|United States',
    'barriedrywall' => 'Barrie|drywall|Ontario|Canada',
    'racialdiscriminationattorney' => '|racial discrimination attorney||',
    'solarenergytennessee' => 'Tennessee|solar energy|Tennessee|United States',
    'kentuckysprayfoaminsulation' => 'Kentucky|spray foam insulation|Kentucky|United States',
    'wisconsinmanagedit' => 'Wisconsin|managed IT|Wisconsin|United States',
    'columbusmetalrecycling' => 'Columbus|metal recycling|Ohio|United States',
    'oaklandlandscapedesign' => 'Oakland|landscape design|California|United States',
    'losangelespreciousmetals' => 'Los Angeles|precious metals|California|United States',
    'jacksonvillejewelers' => 'Jacksonville|jewelers|Florida|United States',
    'houstonbridalphotography' => 'Houston|bridal photography|Texas|United States',
    'phoenixpoolservices' => 'Phoenix|pool services|Arizona|United States',
    'johnsoncityrealtor' => 'Johnson City|realtor|Tennessee|United States',
    'idahointeriordesigner' => 'Idaho|interior designer|Idaho|United States',
    'privateinvestigatorsutah' => 'Utah|private investigators|Utah|United States',
    'nassaucountychiropractic' => 'Nassau County|chiropractic|New York|United States',
    'arizonawelding' => 'Arizona|welding|Arizona|United States',
    'floristhialeah' => 'Hialeah|florist|Florida|United States',
    'bakersfieldrenovations' => 'Bakersfield|renovations|California|United States',
    'laselfdefense' => 'Los Angeles|self defense|California|United States',
    'weddingvenuedenver' => 'Denver|wedding venue|Colorado|United States',
    'lewisvillemovers' => 'Lewisville|movers|Texas|United States',
    'interiordesignsatlanta' => 'Atlanta|interior designs|Georgia|United States',
    'junkremovalfrisco' => 'Frisco|junk removal|Texas|United States',
    'alaskamentalhealth' => 'Alaska|mental health|Alaska|United States',
    'solarenergymassachusetts' => 'Massachusetts|solar energy|Massachusetts|United States',
    'estateattorneyatlanta' => 'Atlanta|estate attorney|Georgia|United States',
    'nashvillemanagedit' => 'Nashville|managed IT|Tennessee|United States',
    'seniorcarenorthcarolina' => 'North Carolina|senior care|North Carolina|United States',
    'coworkingmaine' => 'Maine|coworking|Maine|United States',
    'marketingagencynewjersey' => 'New Jersey|marketing agency|New Jersey|United States',
    'arlingtonmsp' => 'Arlington|MSP|Texas|United States',
    'lawfirmgreensboro' => 'Greensboro|law firm|North Carolina|United States',
    'newyorkrefinishing' => 'New York|refinishing|New York|United States',
    'seattledronephotography' => 'Seattle|drone photography|Washington|United States',
    'legiontech' => '|tech||',
    'photoboothorangecounty' => 'Orange County|photo booth|California|United States',
    'realestatelawsouthflorida' => 'South Florida|real estate law|Florida|United States',
    'injuryattorneysnh' => 'New Hampshire|injury attorneys|New Hampshire|United States',
];

$lines = preg_split('/\r\n|\r|\n/', $raw);
$out = [];
$seen = [];
$errors = [];
$skippedNoPrice = [];
$count = 0;

foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line)) continue;

    $tokens = preg_split('/\s+/', $line);
    $tokens = array_values(array_filter($tokens, fn ($t) => $t !== ''));

    $rawDomain = $tokens[count($tokens) - 1];
    $priceIdx = null;
    foreach ($tokens as $i => $t) {
        if (preg_match('/^[€$]/', $t) && preg_match('/[\d.]+/', $t)) {
            $priceIdx = $i;
            break;
        }
    }
    if ($priceIdx === null) {
        $skippedNoPrice[] = $rawDomain;
        continue;
    }

    $priceToken = $tokens[$priceIdx];
    $isEuro = str_starts_with($priceToken, '€');
    $amount = (float) preg_replace('/[^0-9.]/', '', $priceToken);
    if ($isEuro) $amount = round($amount * 1.1);

    $domain = strtolower(trim($rawDomain));
    $domain = ltrim($domain, '* ');
    $domain = rtrim($domain, '.');
    $ext = '.com';
    if (preg_match('/\.(com|net|org|co)$/', $domain, $m)) $ext = $m[0];
    $base = preg_replace('/\.(com|net|org|co)$/', '', $domain);
    $baseClean = preg_replace('/[^a-z]/', '', $base);
    $fullDomain = $base . $ext;

    if (isset($seen[$fullDomain])) continue;
    $seen[$fullDomain] = true;

    if (!isset($data[$baseClean])) {
        $errors[] = "Unmapped domain: {$fullDomain} ({$baseClean})";
        continue;
    }

    [$city, $keyword, $state, $country] = explode('|', $data[$baseClean]);

    $count++;
    $out[] = "{$fullDomain},{$keyword},{$amount},{$city},{$state},{$country}";
}

echo "=== TOTAL: {$count} records ===\n";
if (!empty($skippedNoPrice)) {
    echo "Skipped (no price): " . count($skippedNoPrice) . "\n" . implode("\n", $skippedNoPrice) . "\n";
}
if (!empty($errors)) {
    echo "ERRORS: " . count($errors) . "\n" . implode("\n", $errors) . "\n";
}

$csv = implode("\n", $out);
file_put_contents(__DIR__ . '/converted_sales_3.csv', $csv);
echo "\n=== CSV OUTPUT ===\n" . $csv . "\n";