<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\QuizAttempts;
use App\Models\QuizUsers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $courses = Course::all();
        //dd($courses);

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Course $course
     * @return Response
     */
    public function show(Course $course)
    {

        $course->load("quizes");

        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Course $course
     * @return Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Course $course
     * @return Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Course $course
     * @return Response
     */
    public function destroy(Course $course)
    {
        //
    }

    public function getAttempts(Request $request)
    {
        $query = $request->get('query');
        $quiz_attempts = QuizAttempts::where('quiz', '=', $query)->get();
        $quiz_users = [];
        foreach ($quiz_attempts as $attempt) {
            $quiz_users = QuizUsers::where("id", $attempt->userid)->get();
        }
        return response()->json([$quiz_attempts, $quiz_users]);
    }

    public function analyze($id)
    {
        $quiz_attempts = DB::connection('mysql2')->select(("SELECT
   concat( u.firstname, ' ', u.lastname ) AS student_name,
   q.id,
   quiza.userid,
   q.course,
   q.name,
   quiza.attempt,
   qa.slot,
   qa.maxmark,
   que.questiontext AS 'question',
   qa.rightanswer AS 'correct_answer',
   qa.responsesummary AS 'student_answer',
   qas.fraction as 'fraction',
   from_unixtime(quiza.timestart) AS 'quiz_started_at',
    (quiza.timestart) AS 'unix_quiz_start',
   from_unixtime(quiza.timefinish) AS 'quiz_finished_at',
   from_unixtime(qas.timecreated) as 'answered_at',
   (qas.timecreated) as 'unix_answered_at'

FROM mdl_quiz_attempts quiza JOIN mdl_quiz q ON q.id=quiza.quiz
    JOIN mdl_question_usages qu ON qu.id = quiza.uniqueid
    JOIN mdl_question_attempts qa ON qa.questionusageid = qu.id
    JOIN mdl_question que ON que.id = qa.questionid
    JOIN mdl_user u ON u.id = quiza.userid
    JOIN mdl_question_attempt_steps qas ON qas.questionattemptid=qa.id
    INNER JOIN (SELECT userid,quiz,MAX(attempt) attempt from mdl_quiz_attempts WHERE quiz='$id' Group by userid) maxattempt on maxattempt.userid=u.id and quiza.attempt=maxattempt.attempt

WHERE q.id = '$id' and qas.state!='todo' and qas.fraction is not null
ORDER BY student_name,unix_answered_at ASC;"));
        $quiz_time = DB::connection('mysql2')->select(("SELECT
   concat( u.firstname, ' ', u.lastname ) AS student_name,
   q.id,

   from_unixtime(qas.timecreated) as 'answered_at',
   (qas.timecreated) as 'unix_answered_at'

FROM mdl_quiz_attempts quiza JOIN mdl_quiz q ON q.id=quiza.quiz
    JOIN mdl_question_usages qu ON qu.id = quiza.uniqueid
    JOIN mdl_question_attempts qa ON qa.questionusageid = qu.id
    JOIN mdl_question que ON que.id = qa.questionid
    JOIN mdl_user u ON u.id = quiza.userid
    JOIN mdl_question_attempt_steps qas ON qas.questionattemptid=qa.id
    INNER JOIN (SELECT userid,quiz,MAX(attempt) attempt from mdl_quiz_attempts WHERE quiz='$id' Group by userid) maxattempt on maxattempt.userid=u.id and quiza.attempt=maxattempt.attempt

WHERE q.id = '$id' and qas.state!='todo' and qas.fraction is null
ORDER BY student_name,unix_answered_at ASC"));

        $quiz_constants = DB::connection('mysql2')->select(("SELECT
   concat( u.firstname, ' ', u.lastname ) AS student_name,
   q.id,
   quiza.userid,
   q.course,
   q.name as 'quiz_name',
   quiza.attempt,
   count(qa.slot) as 'slot',
   sum(qa.maxmark) as 'maxmark',
   sum(qas.fraction) as 'fraction',
   from_unixtime(quiza.timestart) AS 'quiz_started_at',
   from_unixtime(quiza.timefinish) AS 'quiz_finished_at'

FROM mdl_quiz_attempts quiza JOIN mdl_quiz q ON q.id=quiza.quiz
    JOIN mdl_question_usages qu ON qu.id = quiza.uniqueid
    JOIN mdl_question_attempts qa ON qa.questionusageid = qu.id
    JOIN mdl_question que ON que.id = qa.questionid
    JOIN mdl_user u ON u.id = quiza.userid
    JOIN mdl_question_attempt_steps qas ON qas.questionattemptid=qa.id
    INNER JOIN (SELECT userid,quiz,MAX(attempt) attempt from mdl_quiz_attempts WHERE quiz='$id' Group by userid) maxattempt on maxattempt.userid=u.id and quiza.attempt=maxattempt.attempt

WHERE q.id = '$id' and qas.state!='todo' and qas.fraction is not null
GROUP by quiza.userid;"));

        if ($quiz_attempts && $quiz_constants) {
            $const = [];
            foreach ($quiz_constants as $con) {

                $const[$con->student_name] = $con;
            }
;
            $array = json_decode(json_encode($quiz_attempts), true);
            $fill_array = $this->replace_null_with_zero($array);
            $groupByUsers = $this->group_by("student_name", $fill_array);

            $time_array = json_decode(json_encode($quiz_time), true);
            $time_fill_array = $this->replace_null_with_zero($time_array);
            $time_groupByUsers = $this->group_by("student_name", $time_fill_array);

            $fraction = [];
            $max_mark = [];
            $answered_at = [];

            $result = [];
            foreach ($groupByUsers as $user) {

                foreach ($user as $k => $v) {

                    $fraction[$v['student_name']][] = $v['fraction'];
                    $result[$v['student_name']][] = array('Fraction' => $v['fraction'], 'Maxmark' => $v['maxmark'], 'Answered_at' => $v['answered_at']);
                    $max_mark[$v['student_name']][] = $v['maxmark'];


                }

            }

          //moodle query
            $unix_answered_at = [];
            $result_2 = [];
            foreach ($time_groupByUsers as $user) {

                foreach ($user as $k => $v) {

                    $unix_answered_at[$v['student_name']][] = $v['unix_answered_at'];
                    $answered_at[$v['student_name']][] = $v['answered_at'];
                    $result_2[$v['student_name']][] = array('Answered_at' => $v['answered_at']);


                }

            }

            $merge_results = array_replace_recursive($result, $result_2);
            $flat_results = [];
            foreach ($merge_results as $key => $res) {

                $flat_results[$key] = $this->flattenWithKeys($res);
            }

            $merge = array_merge_recursive($flat_results, $const);
            $json_input_marks = json_encode($fraction);

            $path_to_script =  public_path('pyscripts/compare_marks.py');
            $process = new Process(['python3', $path_to_script, $json_input_marks]);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $json_output_marks = $process->getOutput();

           //time spent on question
            $answered_at_time = [];
            foreach ($merge as $key => $time) {
                $timestamp = $time['quiz_started_at'];
                for ($i = 0; $i < $time['slot']; $i++) {
                    //condition required
                    $answered_at_time[$key][] = Carbon::parse($time[$i . '.Answered_at'])->diffInSeconds(Carbon::parse($timestamp));
                    $timestamp = $time[$i . '.Answered_at'];

                }
            }

            $json_input_answered_at = json_encode($answered_at_time);
            $process = new Process(['python3', $path_to_script, $json_input_answered_at]);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $json_output_answered_at = $process->getOutput();



            $avgTimePerQuestionUsers = [];

            foreach ($answered_at_time as $key => $avg) {
                $avgTimePerQuestionUsers[$key]["Average_time_taken"] = array_sum($avg) / count($avg);
            }

            $avgMarkPerQuiz = [];
            foreach ($fraction as $key => $avg) {
                $avgMarkPerQuiz[$key]["Average_mark"] = array_sum($avg);
            }

            $avgTimePerQuestion = array_sum(array_column($avgTimePerQuestionUsers,'Average_time_taken'))/array_sum(array_map("count", $avgTimePerQuestionUsers));
            $avgMarkQuiz = array_sum(array_column($avgMarkPerQuiz,'Average_mark'))/array_sum(array_map("count", $avgMarkPerQuiz));

            $path_to_script2 = public_path('pyscripts/compare_time.py');
            $json_answered_time = json_encode($unix_answered_at);

            $process = new Process(['python3', $path_to_script2, $json_answered_time]);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $json_output_answered_at_similarity = $process->getOutput();
            $json_output_answered_at = json_decode($json_output_answered_at);
            $json_output_answered_at_similarity = json_decode($json_output_answered_at_similarity);
            $json_output_marks = json_decode($json_output_marks);
            $merge = array_merge_recursive($merge, $avgTimePerQuestionUsers);

            return view('courses.analyze', compact('groupByUsers', 'result', 'quiz_constants', 'flat_results', 'merge', 'json_output_answered_at', 'json_output_marks', 'avgTimePerQuestion','avgMarkQuiz','json_output_answered_at_similarity'));
        }

        return view('courses.analyze');

    }


    function flattenWithKeys(array $array, $childPrefix = '.', $root = '', $result = array())
    {
    foreach ($array as $k => $v) {
            if (is_array($v) || is_object($v)) $result = $this->flattenWithKeys((array)$v, $childPrefix, $root . $k . $childPrefix, $result);
            else $result[$root . $k] = $v;
        }
        return $result;
    }


    function replace_null_with_zero($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value))
                $array[$key] = $this->replace_null_with_zero($value);
            else {
                if (is_null($value))
                    $array[$key] = 0;
            }
        }
        return $array;
    }

    function group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }


    function flatten_php_array(array $array)
    {
        $return = array();
        array_walk_recursive($array, function ($a) use (&$return) {
            $return[] = $a;
        });
        return $return;
    }

    function flatty($array, $prefix = '')
    {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + $this->flatty($value, $prefix . $key . '.');
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }
}
