<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Typotest extends Controller {

	public $all_errors = 0;
	public $all_tests  = 0;


	public function action_index()
	{
		$function = Arr::get(array('rmcreative','webfilin','artlebedev'), $this->request->param('id'),'rmcreative');
		$test = $this->{$function}();
		$view = View::factory('typo/test')
				->bind('test',$test)
		        ->set('function',$function)
				->set('all_errors',$this->all_errors)
			    ->set('all_tests',$this->all_tests);
		$this->response->body($view);
	}

	function rmcreative(){
		require kohana::find_file('vendor', 'rmcreative/typographus', 'php');
		$funk = function ($str){
			$typo = new Typographus('UTF-8');
			return $typo->process($str);
		};
		$test = array();
		$test[] = $this->preform_tests($funk, "_test.typo-basic");
		$test[] = $this->preform_tests($funk, "_test.typo-symbols");
		$test[] = $this->preform_tests($funk, "_test.typo-html");
		//$test[] = $this->preform_tests($funk, "_test.typo-phones");
		$test[] = $this->preform_tests($funk, "_test.typo-quotes");
		$test[] = $this->preform_tests($funk, "_test.typo-latest");
		return $test;
	}

	function webfilin(){
		require kohana::find_file('vendor', 'webfilin/typograf', 'php');
		$funk = function ($str){
			$typograf = new Typograf();
			return $typograf->execute($str, TYPOGRAF_MODE_NAMES);
		};
		$test = array();
		$test[] = $this->preform_tests($funk, "_test.typo-basic");
		$test[] = $this->preform_tests($funk, "_test.typo-symbols");
		$test[] = $this->preform_tests($funk, "_test.typo-html");
		//$test[] = $this->preform_tests($funk, "_test.typo-phones");
		$test[] = $this->preform_tests($funk, "_test.typo-quotes");
		$test[] = $this->preform_tests($funk, "_test.typo-latest");
		return $test;
	}

	function artlebedev(){
		require kohana::find_file('vendor', 'artlebedev/remotetypograf', 'php');
		$funk = function ($str){
			$remoteTypograf = new RemoteTypograf('UTF-8');
			$remoteTypograf->htmlEntities();
			$remoteTypograf->br (false);
			$remoteTypograf->p (true);
			$remoteTypograf->nobr (3);
			$remoteTypograf->quotA ('laquo raquo');
			$remoteTypograf->quotB ('bdquo ldquo');
			return $remoteTypograf->processText($str);
		};
		$test = array();
		$test[] = $this->preform_tests($funk, "_test.typo-basic");
		$test[] = $this->preform_tests($funk, "_test.typo-symbols");
		$test[] = $this->preform_tests($funk, "_test.typo-html");
		//$test[] = $this->preform_tests($funk, "_test.typo-phones");
		$test[] = $this->preform_tests($funk, "_test.typo-quotes");
		$test[] = $this->preform_tests($funk, "_test.typo-latest");
		return $test;
	}

	public static function show_entities($str){
		$entities = array(
			'&nbsp;' => '•',
		);
		return htmlspecialchars(str_replace(array_keys($entities), array_values($entities), $str));
	}

	public function preform_tests($func, $test_file){
		$data['file']   = $test_file;
		$execute_test   = true;
		$data['tests']  = 0;
		$data['errors'] = 0;
		$data['message'] = array();
		$file = file(Kohana::find_file('vendor', 'test/'.$test_file, 'dat'));
		foreach ($file as $line_number => $str){
			$str = trim(iconv('WINDOWS-1251', 'UTF-8', $str));
			if ($str && !preg_match('~^#~', $str)){
				if ($execute_test){
					$in  = $str;
					$out = call_user_func_array($func, array($in));
					$data['tests']++;
					$this->all_tests++;
				}
				else{
					$cfg = $str;
					if ($out != $cfg){
						$message = array();
						$message['line'] = ++$line_number;
						$message['in']   = Controller_Typotest::show_entities($in);
						$message['out']  = Controller_Typotest::show_entities($out);
						$message['cfg']  = Controller_Typotest::show_entities($cfg);
						$data['message'][] = $message;
						$data['errors']++;
						//-
						$this->all_errors++;
					}
				}
				$execute_test = !$execute_test;
			}
		}
		return $data;
	}
} // End Typotest