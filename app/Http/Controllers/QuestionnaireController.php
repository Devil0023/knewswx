<?php

namespace App\Http\Controllers;

use App\Models\Questioninfo;
use App\Models\Questionnaire;
use App\Models\Surveyresult;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cookie;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {

        $questionnaire = Questionnaire::find($request->id);
        if(!@$questionnaire->id || @intval($questionnaire->checked) != 1){
            header("HTTP/1.1 404 Not Found"); die;
        }

        $do     = "1";
        $cookie = $request->cookie("KnewsQuestionnaire-".$questionnaire->id);

        if(is_null($cookie)){
            $do = "0";
        }

        $questions = $this->getQuestions($questionnaire->id);

        return view("survey.index", compact("questionnaire","questions", "do"));

    }

    public function submit(Request $request){

        $code = @session('knewsquestionnaire.code');

        $questionnaire = Questionnaire::find($request->id);
        if(!@$questionnaire->id || @intval($questionnaire->checked) != 1){
            header("HTTP/1.1 404 Not Found"); die;
        }

        $questions = $this->getQuestions($questionnaire->id);

        $survey    = array();

        foreach($questions as $val){
            $var_name = "Question_".$val["qorder"];
            $survey[$val["qorder"].".".$val["question"]] = is_null(@$request->$var_name)? "": @$request->$var_name;
        }

        $surveyresult = Surveyresult::create(array(
            "code" => $code,
            "qid"  => $questionnaire->id,
            "questionaire" => json_encode($survey),
        ));

        if($surveyresult){
            $result["error_code"]    = "0";
            $result["error_message"] = "success";

            Cookie::queue('KnewsQuestionnaire-'.$questionnaire->id, md5(time()), 10080);
        }else{
            $result["error_code"]    = "400002";
            $result["error_message"] = "保存失败！";
        }

        return $result;
    }

    public function check(Request $request){

        $result["error_code"]    = "0";
        $result["error_message"] = "success";

        $code = @$request->jobNumber;

        if(empty($code) || $this->checkCode($code) === false){
            $result["error_code"]    = "400001";
            $result["error_message"] = "工号不存在！";
        }else{
            session(['knewsquestionnaire.code' => $code]);
        }

        return $result;
    }

    private function checkCode($code){

        $list = array(

            "01000416",
            "01001537",
            "01000490",
            "01000504",
            "01001538",
            "01000492",
            "01000555",
            "01000621",
            "01000584",
            "01001563",
            "01000574",
            "01001557",
            "01001465",
            "01001473",
            "01000710",
            "01000596",
            "01004422",
            "01000565",

            "01001602",
            "01000233",
            "01001211",
            "01000501",
            "01002169",
            "01000656",
            "01000899",
            "01000552",
            "01005289",
            "01002382",
            "01000657",
            "01000509",
            "01001623",
            "01000651",
            "01001609",
            "01000623",
            "01002789",
            "01000695",
            "01005429",
            "01990176",
            "01750008",
            "01920623",
            "01990278",
            "01990096",
            "01003064",
            "01006102",
            "01000950",
            "01005381",
            "01001582",
            "01001468",
            "01000384",
            "01001007",
            "01000569",
            "01003562",
            "01004804",
            "01003429",
            "01000637",
            "01001535",
            "01001608",
            "01001476",
            "01000693",
            "01000291",
            "01001588",
            "01005402",
            "01001494",
            "01003352",
            "01000694",
            "01000898",
            "01001488",
            "01001718",
            "01000595",
            "01004003",
            "01860101",
            "01005253",
            "01750149",
            "01990302",
            "01000841",
            "01001496",
            "01000493",
            "01001474",
            "01000708",
            "01001006",
            "01000949",
            "01003725",
            "01000722",
            "01003971",
            "01001019",
            "01000703",
            "01001581",
            "01001030",
            "01000721",
            "01001034",
            "01000975",
            "01003660",
            "01001021",
            "01001489",
            "01003642",
            "01001043",
            "01003947",
            "01003996",
            "01004228",
            "01003874",
            "01004446",
            "01000700",
            "01004502",
            "01930047",
            "01003948",
            "01000690",
            "01003646",
            "01930048",
            "01000711",
            "01003946",
            "01003658",
            "01003663",
            "01004508",
            "01990256",
            "01000967",
            "01001564",
            "01740044",
            "01000546",
            "01000643",
            "01740050",
            "01000545",
            "01001499",
            "01001566",
            "01740149",
            "01740036",
            "01740040",
            "01740176",
            "01740211",
            "01740209",
            "01740226",
            "01740225",
            "01740297",
            "01740319",
            "01740282",
            "01990110",
            "01740128",
            "01990113",
            "01872090",
            "01990125",
            "01990124",
            "01740177",
            "01990185",
            "01740210",
            "01005382",
            "01005924",
            "01003413",
            "01004184",
            "01990209",
            "01000641",
            "01004110",
            "01004111",
            "01005772",
            "01003994",
            "01005803",
            "01005995",
            "01005774",
            "01000639",
            "01003552",
            "01872061",
            "01872069",
            "01872075",
            "01872079",
            "01872080",
            "01872074",
            "01872088",
            "01810093",
            "01872058",
            "01980070",
            "01990130",
            "01990131",
            "01990137",
            "01990150",
            "01990177",
            "01990178",
            "01990275",
            "01005437",
            "01001035",
            "01003829",
            "01003828",
            "01990244",
            "01000566",
            "01000712",
            "01001022",
            "01001029",
            "01000707",
            "01004186",
            "01003998",
            "01004227",
            "01003791",
            "01000701",
            "01004187",
            "01003949",
            "01003966",
            "01001023",
            "01003972",
            "01006035",
            "01004438",
            "01002685",
            "01001493",
            "01000716",
            "01001024",
            "01003951",
            "01001550",
            "01000568",
            "01003596",
            "01003952",
            "01000715",
            "01000567",
            "01001555",
            "01001031",
            "01003723",
            "01000531",
            "01000719",
            "01000537",
            "01004430",
            "01004509",
            "01740118",
            "01740114",
            "01990231",
            "01990240",
            "01000132",
            "01003396",
            "01004564",
            "01000631",
            "01003647",
            "01005532",
            "01005293",
            "01990228",
            "01990198",
            "01920074",
            "01004423",
            "01710107",
            "01003308",
            "01004964",
            "01002383",
            "01003321",
            "01750011",
            "01002407",
            "01003304",
            "01003333",
            "01003311",
            "01003448",
            "01005852",
            "01750028",
            "01003360",
            "01990074",
            "01930223",
            "01005416",
            "01990029",
            "01006001",
            "01840071",
            "14000006",
            "01000551",
            "01001607",
            "01000628",
            "01000662",
            "01003839",
            "01000646",
            "01001521",
            "01000576",
            "01003766",
            "01004096",
            "01003392",
            "01003452",
            "01000633",
            "01005521",
            "01990049",
            "01005420",
            "01750076",
            "01003610",
            "01005527",
            "01005889",
            "01005422",
            "01003859",
            "01003601",
            "01000964",
            "01000558",
            "01005921",
            "01000681",
            "01000680",
            "01000640",
            "01000564",
            "01000550",
            "01004776",
            "01000556",
            "01005612",
            "01000683",
            "01005453",
            "01003457",
            "01005419",
            "01005817",
            "01000634",
            "01002234",
            "01001001",
            "01000625",
            "01005979",
            "01006059",
            "01005506",
            "01005518",
            "01990041",
            "01990304",
            "01900022",
            "01981119",
            "01000871",
            "01001042",
            "01003662",
            "01004435",
            "01004436",
            "01004437",
            "01004538",
            "01001556",
            "01000644",
            "01003549",
            "01004757",
            "01005236",
            "01005860",
            "01000573",
            "01000670",
            "01003415",
            "01003657",
            "01001054",
            "01001004",
            "01000604",
            "01001554",
            "01003654",
            "01006006",
            "01000571",
            "01003959",
            "01000895",
            "01000987",
            "01000575",
            "01000663",
            "01720102",
            "01001635",
            "01003968",
            "01003967",
            "01003405",
            "01000580",
            "01004218",
            "01003799",
            "01000647",
            "01006083",
            "01003981",
            "01001553",
            "01003787",
            "01000872",
            "01003464",
            "01000577",
            "01001558",
            "01003973",
            "01004388",
            "01001562",
            "01001561",
            "01990145",
            "01990148",
            "01990144",
            "01990147",
            "01990149",
            "01990236",
            "01990204",
            "01990213",
            "01990290",
            "01990299",
            "01990300",
            "01990301",
            "01990305",
            "01720053",
            "01000996",
            "01000991",
            "01000626",
            "01005865",
            "01000534",
            "01000500",
            "01000594",
            "01001612",
            "01003614",
            "01003487",
            "01005894",
            "01001444",
            "01004409",
            "01001547",
            "01005421",
            "01003965",
            "01005926",
            "01003950",
            "01006009",
            "01005976",
            "01000702",
            "01000720",
            "01005230",
            "01005020",
            "01003830",
            "01005831",
            "01004694",
            "01990187",
            "01006060",
            "01000535",
            "01000632",
            "01001618",
            "01001596",
            "01004693",
            "01005019",
            "01005775",
            "01000497",
            "01005932",
            "01750065",
            "01000583",
            "01003974",
            "01003969",
            "01001018",
            "01001546",
            "01005098",
            "01001560",
            "01000561",
            "01000650",
            "01000667",
            "01003875",
            "01000620",
            "01000669",
            "01003975",
            "01000613",
            "01000691",
            "01000713",
            "01003970",
            "01000538",
            "01000532",
            "01000699",
            "01810147",
            "01872057",
            "01000684",
            "01005978",
            "01000597",
            "01003460",
            "01000525",
            "01001572",
            "01004105",
            "01005824",
            "01003455",
            "01750083",
            "01006056",
            "01990162",
            "01920137",
            "01990203",
            "01001591",
            "01000686",
            "01005021",
            "01000533",
            "01001515",
            "01000618",
            "01001570",
            "01000674",
            "01001503",
            "01001495",
            "01005771",
            "01930066",
            "01000894",
            "01000660",
            "01003903",
            "01000709",
            "01003961",
            "01005920",
            "01000559",
            "01004367",
            "01004847",
            "01006037",
            "01001045",
            "01001837",
            "01002097",
            "01003842",
            "01006005",
            "01005557",
            "01000627",
            "01003592",
            "01004183",
            "01003151",
            "01003835",
            "01005930",
            "01005934",
            "01990174",
            "01990249",
            "01990269",
            "01990183",
            "01990222",
            "01003796",
            "01000760",
            "01000549",
            "01001055",
            "01005026",
            "01005928",
            "01005929",
            "01006055",
            "01006058",
            "01990258",
            "01005428",
            "01990260",
            "01005023",
            "01005259",
            "01003980",
            "01990191",
            "01990217",
            "01990226",
            "01001977",
            "01004512",
            "01004889",
            "01005805",
            "01004692",
            "01000696",
            "01005233",
            "01990180",
            "01990151",
            "01930121",
            "01005955",
            "01003582",
            "01990190",
            "01005820",
            "01004799",
            "01990239",
            "01004479",
            "01005345",
            "01006116",
            "01990238",
            "01990234",
            "01005028",
            "01005880",
            "01005346",
            "01990297",
            "01001611",
            "01003670",
            "01004095",
            "01003490",
            "01004883",
            "01720357",
            "01006007",
            "01990182",
            "01990212",
            "01920885",
            "01005974",
            "01990028",
            "01004795",
            "01001529",
            "01005833",
            "01720195",
            "01005827",
            "01004100",
            "01005913",
            "01005840",
            "01000612",
            "01005426",
            "01005376",
            "01000598",
            "01006057",
            "01001405",
            "01990266",
            "01003077",
            "01003423",
            "01001518",
            "01001036",
            "01004271",
            "01830690",
            "01990175",
            "01000512",
            "01000665",
            "01003837",
            "01000887",
            "01000913",
            "01005902",
            "01005377",
            "01001571",
            "01003599",
            "01004700",
            "01760063",
            "01005895",
            "01003458",
            "01001540",
            "01000962",
            "01003462",
            "01005512",
            "01000863",
            "01990216",
            "01990242",
            "01990277",
            "01000617",
            "01990116",
            "01990135",
            "01000952",
            "01005268",
            "01003954",
            "01003953",
            "01003600",
            "01004536",
            "01990089",
            "01990250",
            "01001525",
            "01003956",
            "01000932",
            "01001009",
            "01000883",
            "01003613",
            "01990193",
            "01000692",
            "01000619",
            "01005423",
            "01000648",
            "01001511",
            "01003957",
            "01000521",
            "01990166",
            "01006113",
            "01990172",
            "01004104",
            "01005931",
            "01000889",
            "01005830",
            "01005918",
            "01004097",
            "01990154",
            "01990169",
            "01990215",
            "01990274",
            "01990210",
            "01004555",
            "01000677",
            "01004103",
            "01003493",
            "01004659",
            "01005832",
            "01003855",
            "01005933",
            "01990173",
            "01990181",
            "01990227",
            "01990255",
            "01990171",
            "01006119",
            "01001038",
            "01872050",
            "01000977",
            "01000955",
            "01003841",
            "01001483",
            "01000873",
            "01004032",
            "01001027",
            "01000838",
            "01000922",
            "01003989",
            "01930069",
            "01990194",
            "01990254",
            "01004848",
            "01930029",
            "01004699",
            "01003637",
            "01930068",
            "01003825",
            "01005424",
            "01001506",
            "01001039",
            "01005810",
            "01990223",
            "01990235",
            "01930067",
            "01840053",
            "01000966",
            "01003793",
            "01990163",
            "01006032",
            "01990168",
            "01000562",
            "01000993",
            "01003962",
            "01004107",
            "01000930",
            "01000999",
            "01001597",
            "01003639",
            "01003995",
            "01005658",
            "01001917",
            "01004949",
            "01990184",
            "01990186",
            "01990224",
            "01990253",
            "01990259",
            "01000968",
            "01001051",
            "01003782",
            "01003604",
            "01730133",
            "01004506",
            "01006118",
            "01005989",
            "01920070",
            "01004112",
            "01990220",
            "01720762",
            "01990257",
            "01005919",
            "01990219",
            "01005822",
            "01001587",
            "01003999",
            "01005290",
            "01005256",
            "01005264",
            "01005519",
            "01005286",
            "01005530",
            "01990048",
            "01990012",
            "01990064",
            "01005515",
            "01990004",
            "01990121",
            "01990123",
            "01990143",
            "01990196",
            "01990201",
            "01990206",
            "01990208",
            "01990246",
            "01990127",
            "01000629",
            "01005252",
            "01005097",
            "01005292",
            "01990015",
            "01990158",
            "01005535",
            "01990098",
            "01005261",
            "01005287",
            "01990111",
            "01990122",
            "01720356",
            "01990139",
            "01990141",
            "01990142",
            "01990195",
            "01990071",
            "01990066",
            "01990161",
            "01990199",
            "01001624",
            "01990001",
            "01005541",
            "01920460",
            "01990083",
            "01990230",
            "01730107",
            "01005291",
            "01005529",
            "01005516",
            "01990039",
            "01990043",
            "01990153",
            "01990078",
            "01990126",
            "01990101",
            "01990170",
            "01990032",
            "01004612",
            "01990067",
            "01990024",
            "01990264",
            "01990268",
            "01990270",
            "01990134",
            "01990271",
            "01990248",
            "01990129",
            "01990076",
            "01990087",
            "01004580",
            "01990097",
            "01990263",
            "01990120",
            "01990119",
            "01990152",
            "01000446",
            "01990031",
            "01990092",
            "01990164",
            "01990279",
            "01990280",
            "01990281",
            "01990282",
            "01990283",
            "01990284",
            "01990285",
            "01990286",
            "01990288",
            "01990289",
            "01990291",
            "01990292",
            "01990293",
            "01990294",
            "01990295",
            "01990296",
            "01990298",
            "01000630",
            "01001621",
            "01005587",
            "01005018",
            "01990188",
            "01005888",
            "01720898",
            "01880001",
            "01005829",
            "01003935",
            "01990211",
            "01990251",
            "01005922",
            "01000718",
            "01990218",
            "01990237",
            "01003422",
            "01002627",
            "01720153",
            "01990136",
            "01990140",
            "01005295",
            "01005511",
            "01990020",
            "01990112",
            "01000635",
            "01000916",
            "01000609",
            "01003453",
            "01004697",
            "01005981",
            "01003810",
            "01005885",
            "01720799",
            "01004824",
            "01005982",
            "01001628",
            "01001626",
            "01720172",
            "00010118",
            "01990273",
            "01001508",
            "01003461",
            "01005973",
            "01006003",
            "01720381",
            "01990200",
            "01990205",
            "01990207",
            "01000671",
            "01004505",
            "01000488",
            "01001505",
            "01001575",
            "01004289",
            "01004696",
            "01001620",
            "01005877",
            "01910032",
            "01005966",
            "01005965",
            "01005990",
            "01990267",
            "01000697",
            "01000664",
            "01005024",
            "01001486",
            "01004742",
            "01000679",
            "01720614",
            "01004351",
            "01004763",
            "01004274",
            "01005977",
            "01750055",
            "01005025",
            "01000536",
            "01004856",
            "01001498",
            "01002378",
            "01000880",
            "01004291",
            "01004898",
            "01990003",
            "01005269",
            "01005874",
            "01000499",
            "01001577",
            "01004701",
            "01001604",
            "01001605",
            "01000672",
            "01000705",
            "01004099",
            "01003978",
            "01004094",
            "01003960",
            "01001627",
            "01001492",
            "01001578",
            "01001532",
            "01001579",
            "01990272",
            "01000929",
            "01004412",
            "01004755",
            "01005434",
            "01004791",
            "01006129",
            "01006107",
            "01004266",
            "01005696",
            "01004522",
            "01005985",
            "01006044",
            "01006064",
            "01006070",
            "01990262",
            "01990287",
            "01000925",
            "01004477",
            "01005281",
            "01005891",
            "01004453",
            "01006126",
            "01005873",
            "01006078",
            "01000842",
            "01003788",
            "01000969",
            "01003913",
            "01000928",
            "01004264",
            "01004147",
            "01004533",
            "01005035",
            "01006115",
            "01000933",
            "01000864",
            "01004085",
            "01004525",
            "01004759",
            "01005133",
            "01005941",
            "01006094",
            "01004951",
            "01005821",
            "01001133",
            "01004880",
            "01004761",
            "01006048",
            "01004442",
            "01005164",
            "01006106",
            "01005939",
            "01003992",
            "01004445",
            "01006145",
            "01005033",
            "01004758",
            "01004967",
            "01004415",
            "01003840",
            "01004122",
            "01001132",
            "01005431",
            "01005809",
            "01003707",
            "01004428",
            "01000900",
        );

        return in_array($code, $list);
    }

    private function getQuestions($qid){
        $qkey = "Questionnaire-".$qid;
        $json = @Redis::get($qkey);

        if(empty($json)){

            $json = Questioninfo::where("qid", $qid)
                ->where("deleted_at", null)
                ->orderBy("qorder", "asc")->get()->toJson();

            @Redis::setex($qkey, 300, $json);
        }

        return json_decode($json, true);
    }
}
