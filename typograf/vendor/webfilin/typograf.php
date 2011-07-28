<?php
/*
	"Типограф" для каждого
	Автор: Селезнёв Д. Л., info@webfilin.ru,  http://webfilin.ru/instruments/typograf/
	Версия: 1.0
	Дата: 17.01.2008

	Лицензия: GNU Public License
*/ 

define('TYPOGRAF_MODE_DEFAULT', 0); // &#8212; - спец. символы в виде кодов
define('TYPOGRAF_MODE_NAMES', 1); // &mdash; - спец. символы в виде имён
define('TYPOGRAF_MODE_OLD', 2); // &#151; - спец. символы в виде устарев. кодов, для "гурманов"

class Typograf
{
	private $text; // Текст для типографирования
	private $tmode; // Режим типографа
	
	public $br; // Растановка переносов строк,  true или false
	public $p; // Растановка абзацов,  true или false
	
	public $quot11; // Откр. кавычка первого уровня
	public $quot12; // Закр. кавычка первого уровня
	public $quot21; // Откр. кавычка второго уровня
	public $quot22; // Закр. кавычка второго уровня
	
	public $tire; // Тире
	public $tireinterval; // Тире в интервалах
	public $number; // Знак №
	public $sect; // Знак параграфа
	public $sup2; // Степень квадрата
	public $sup3; // Степень куба
	public $deg; // Знак градуса
	public $Prime; // Знак дюйма
	public $euro; // Знак евро
	public $times; // Знак умножения
	public $plusmn; // Плюс-минус
	
	public $space; // Неразрывный пробел
	public $spaceAfterShortWord; // Пробел после коротких слов,  true или false
	public $lengthShortWord; // Длина короткого слова
	public $spaceBeforeTire; // Пробел перед тире,  true или false
	public $delTab;	// Удаление табов, если установлено false, табы заменяются на пробелы,  true или false
	public $replaceTab; // Замена табов на пробелы,  true или false
	public $spaceBeforeLastWord; // Пробел перед последним словом,  true или false
	public $lengthLastWord; // Длина последнего слова
	public $spaceAfterNum; // Пробел после №,  true или false
	
	public $spaceBeforeParticles; // Пробел перед частицами - ли, же, бы.  true или false
	public $delRepeatSpace; // Удалять повторы пробелов,  true или false
	public $delSpaceBeforePunctuation; // Удалять пробел перед знаками препинания,  true или false
	public $delSpaceBeforeProcent; // Удалять пробел перед знаком процента,  true или false
	public $trim; // Удаление пробелов в начале и конце текста,  true или false
	
	public $doReplaceBefore; // Делать замену перед типографированием. true или false
	public $doReplaceAfter; // Делать замену после типографирования. true или false
	
	public $findUrls; // Искать URL и заменять http://example.com на  <a href="http://example.com">http://example.com</a>,  true или false

	private $_isHTMLCode; // Это HTML-код

	function __construct() 
	{
		$this->tmode=TYPOGRAF_MODE_DEFAULT;
		
		$this->p=false;
		$this->br=false;
		
		$this->tmode=TYPOGRAF_MODE_DEFAULT;
		$this->quot11='&#171;';
		$this->quot12='&#187;';
		$this->quot21='&#8222;';
		$this->quot22='&#8220;';
		
		$this->tire='&#8212;';
		$this->tireinterval='&#8212;';
		$this->number='&#8470;';
		$this->hellip='&#8230;';
		$this->sect='&#167;';
		$this->sup2='&#178;';
		$this->sup3='&#179;';
		$this->deg='&#176;';
		$this->euro='&#8364;';
		$this->cent='&#162;';
		$this->pound='&#163;';
		$this->Prime='&#8243;';
		$this->times='&#215;';
		$this->plusmn='&#177;';
		
		$this->darr='&#8595;';
		$this->uarr='&#8593;';
		$this->larr='&#8592;';
		$this->rarr='&#8594;';
		$this->crarr='&#8629;';
		
		$this->space='&#160;';
		$this->delRepeatSpace=true;
		$this->delTab=false;
		$this->replaceTab=true;
		$this->spaceBeforeParticles=true;
		$this->spaceAfterShortWord=true;
		$this->lengthShortWord=2;
		$this->spaceBeforeTire=true;	
		$this->spaceBeforeLastWord=true;
		$this->lengthLastWord=3;
		$this->delSpaceBeforePunctuation=true;
		$this->delSpaceBeforeProcent=true;
		$this->spaceAfterNum=true;
		$this->trim=true;
		
		$this->findUrls=false;
		
		$this->doReplaceBefore=true;
		$this->doReplaceAfter=true;
	}
	
	private function replaceBefore()
	{
		$before=array('(r)', '(c)', '(tm)', '+/-');
		$after=array('®', '©', '™', '±');
		
		$this->text=str_ireplace($before, $after, $this->text);
		
		return;
	}

	private function replaceAfter()
	{
		// Замена +- около чисел
		$this->text=preg_replace('/(?<=^| |\>|&nbsp;|&#160;)\+-(?=\d)/', $this->plusmn, $this->text);
		
		// Замена 3 точек на троеточие
		$this->text=preg_replace('/(^|[^.])\.{3}([^.]|$)/', '$1'.$this->hellip.'$2', $this->text);
		
		// Градусы Цельсия
		$this->text=preg_replace('/(\d+)( |\&\#160;|\&nbsp;)?(C|F)([\W \.,:\!\?"\]\)]|$)/', '$1'.$this->space.$this->deg.'$3$4', $this->text);
		
		// XXXX г.
		$this->text=preg_replace('/(^|\D)(\d{4})г( |\.|$)/', '$1$2'.$this->space.'г$3', $this->text);
		
		$m='(км|м|дм|см|мм)';
		// Кв. км м дм см мм
		$this->text=preg_replace('/(^|\D)(\d+)( |\&\#160;|\&nbsp;)?'.$m.'2(\D|$)/', '$1$2'.$this->space.'$4'.$this->sup2.'$5', $this->text);

		// Куб. км м дм см мм
		$this->text=preg_replace('/(^|\D)(\d+)( |\&\#160;|\&nbsp;)?'.$m.'3(\D|$)/', '$1$2'.$this->space.'$4'.$this->sup3.'$5', $this->text);

		// ГРАД(n)
		$this->text=preg_replace('/ГРАД\(([\d\.,]+?)\)/', '$1'.$this->deg, $this->text);
		
		// ДЮЙМ(n)
		$this->text=preg_replace('/ДЮЙМ\(([\d\.,]+?)\)/', '$1'.$this->Prime, $this->text);

		// Замена икса в числах на знак умножения
		$this->text=preg_replace('/(?<=\d) ?x ?(?=\d)/', $this->times, $this->text);
		
		// Знак евро
		$this->text=str_replace('ЕВРО()', $this->euro, $this->text);

		// Знак фунта
		$this->text=str_replace('ФУНТ()', $this->pound, $this->text);

		// Знак цента
		$this->text=str_replace('ЦЕНТ()', $this->cent, $this->text);
		
		// Стрелка вверх
		$this->text=str_replace('СТРВ()', $this->uarr, $this->text);		
		
		// Стрелка вниз
		$this->text=str_replace('СТРН()', $this->darr, $this->text);		

		// Стрелка влево
		$this->text=str_replace('СТРЛ()', $this->larr, $this->text);		

		// Стрелка вправо
		$this->text=str_replace('СТРП()', $this->rarr, $this->text);		

		// Стрелка ввод
		$this->text=str_replace('ВВОД()', $this->crarr, $this->text);		

		return;
	}

	public function setTMode($tmode=TYPOGRAF_MODE_DEFAULT)
	{
		if ($tmode==TYPOGRAF_MODE_DEFAULT)
		{
			$this->tmode=TYPOGRAF_MODE_DEFAULT;
			
			$this->quot11='&#171;';
			$this->quot12='&#187;';
			$this->quot21='&#8222;';
			$this->quot22='&#8220;';			

			$this->tire='&#8212;';
			$this->tireinterval='&#8212;';
			
			$this->space='&#160;';
			$this->hellip='&#8230;';

			$this->sect='&#167;';
			$this->sup2='&#178;';
			$this->sup3='&#179;';
			$this->deg='&#176;';
			
			$this->euro='&#8364;';
			$this->cent='&#162;';
			$this->pound='&#163;';
			$this->Prime='&#8243;';
			$this->times='&#215;';
			$this->plusmn='&#177;';
			
			$this->darr='&#8595;';
			$this->uarr='&#8593;';
			$this->larr='&#8592;';
			$this->rarr='&#8594;';
			$this->crarr='&#8629;';			
		}
		else if ($tmode==TYPOGRAF_MODE_NAMES)
		{
			$this->tmode=TYPOGRAF_MODE_NAMES;
			
			$this->quot11='&laquo;';
			$this->quot12='&raquo;';
			$this->quot21='&bdquo;';
			$this->quot22='&ldquo;';			

			$this->tire='&mdash;';
			$this->tireinterval='&mdash;';
			
			$this->space='&nbsp;';
			$this->hellip='&hellip;';

			$this->sect='&sect;';
			$this->sup2='&sup2;';
			$this->sup3='&sup3;';
			$this->deg='&deg;';
			
			$this->euro='&euro;';
			$this->cent='&cent;';
			$this->pound='&pound;';			
			$this->Prime='&Prime;';
			$this->times='&times;';
			$this->plusmn='&plusmn;';

			$this->darr='&darr;';
			$this->uarr='&uarr;';
			$this->larr='&larr;';
			$this->rarr='&rarr;';
			$this->crarr='&crarr;';			
		}
		else
		{
			$tmode==TYPOGRAF_MODE_OLD;

			$this->quot11='&#171;';
			$this->quot12='&#187;';
			$this->quot21='&#132;';
			$this->quot22='&#147;';			

			$this->tire='&#151;';
			$this->tireinterval='&#151;';
			
			$this->space='&#160;';
			$this->hellip='&#133;';
			
			$this->sect='&#167;';
			$this->sup2='&#178;';
			$this->sup3='&#179;';
			$this->deg='&#176;';
			$this->euro='&#8364;';
			$this->cent='&#162;';
			$this->pound='&#163;';			
			$this->Prime='&#8243;';
			$this->times='&#215;';
			$this->plusmn='&#177;';
		
			$this->darr='&#8595;';
			$this->uarr='&#8593;';
			$this->larr='&#8592;';
			$this->rarr='&#8594;';
			$this->crarr='&#8629;';			
		}
		
		return;
	}

	private function quotes()
	{
		$quotes=array('&quot;', '&laquo;', '&raquo;', '«', '»', '&#171;', '&#187;', '&#147;', '&#132;', '&#8222;', '&#8220;');
		$this->text=str_replace($quotes, '"', $this->text);

		$this->text=preg_replace('/([^=]|\A)""(\.{2,4}[а-яА-Я\w\-]+|[а-яА-Я\w\-]+)/', '$1<typo:quot1>"$2', $this->text);
		$this->text=preg_replace('/([^=]|\A)"(\.{2,4}[а-яА-Я\w\-]+|[а-яА-Я\w\-]+)/', '$1<typo:quot1>$2', $this->text);
		
		$this->text=preg_replace('/([а-яА-Я\w\.\-]+)""([\n\.\?\!, \)][^>]{0,1})/', '$1"</typo:quot1>$2', $this->text);
		$this->text=preg_replace('/([а-яА-Я\w\.\-]+)"([\n\.\?\!, \)][^>]{0,1})/', '$1</typo:quot1>$2', $this->text);
		
		$this->text=preg_replace('/(<\/typo:quot1>[\.\?\!]{1,3})"([\n\.\?\!, \)][^>]{0,1})/', '$1</typo:quot1>$2', $this->text);
		$this->text=preg_replace('/(<typo:quot1>[а-яА-Я\w\.\- \n]*?)<typo:quot1>(.+?)<\/typo:quot1>/', '$1<typo:quot2>$2</typo:quot2>', $this->text);
		$this->text=preg_replace('/(<\/typo:quot2>.+?)<typo:quot1>(.+?)<\/typo:quot1>/', '$1<typo:quot2>$2</typo:quot2>', $this->text);
		$this->text=preg_replace('/(<typo:quot2>.+?<\/typo:quot2>)\.(.+?<typo:quot1>)/', '$1<\/typo:quot1>.$2', $this->text);
		$this->text=preg_replace('/(<typo:quot2>.+?<\/typo:quot2>)\.(?!<\/typo:quot1>)/', '$1</typo:quot1>.$2$3$4', $this->text);
		$this->text=preg_replace('/""/', '</typo:quot2></typo:quot1>', $this->text);
		$this->text=preg_replace('/(?<=<typo:quot2>)(.+?)<typo:quot1>(.+?)(?!<\/typo:quot2>)/', '$1<typo:quot2>$2', $this->text);
		$this->text=preg_replace('/"/', '</typo:quot1>', $this->text);
		
		$this->text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $this->text);
		$this->text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $this->text);
		$this->text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $this->text);
		$this->text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $this->text);
		$this->text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $this->text);
		$this->text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $this->text);
	
		$this->text=str_replace('<typo:quot1>', $this->quot11, $this->text);
		$this->text=str_replace('</typo:quot1>', $this->quot12, $this->text);
		$this->text=str_replace('<typo:quot2>', $this->quot21, $this->text);
		$this->text=str_replace('</typo:quot2>', $this->quot22, $this->text);
		
		return;
	}

	private function dashes()
	{
		$tires=array('&mdash;', '&ndash;', '&#8211;', '&#8212;');
		$this->text=str_replace($tires, '—', $this->text);

		$pre='(январь|февраль|март|апрель|июнь|июль|август|сентябрь|октябрь|ноябрь|декабрь)';
		$this->text=preg_replace('/'.$pre.' ?(-|—) ?'.$pre.'/i', '$1—$3', $this->text);
		
		$pre='(понедельник|вторник|среда|четверг|пятница|суббота|воскресенье)';
		$this->text=preg_replace('/'.$pre.' ?(-|—) ?'.$pre.'/i', '$1—$3', $this->text);		

		$this->text=preg_replace('/(^|\n|>) ?(-|—) /', '$1— ', $this->text);
		
		$this->text=preg_replace('/(^|[^\d\-])(\d{1,4}) ?(—|-) ?(\d{1,4})([^\d\-\=]|$)/', '$1$2'.$this->tireinterval.'$4$5', $this->text);
		
		$this->text=preg_replace('/ -(?= )/', $this->space.$this->tire, $this->text);
		$this->text=preg_replace('/(?<=&nbsp;|&#160;)-(?= )/', $this->tire, $this->text);

		$this->text=preg_replace('/ —(?= )/', $this->space.$this->tire, $this->text);
		$this->text=preg_replace('/(?<=&nbsp;|&#160;)—(?= )/', $this->tire, $this->text);
		
		return;
	}
	
	private function pbr()
	{
		$n=strpos($this->text, "\n");
		
		if ($this->_isHTMLCode)	return;
		
		if ($n!==false)
		{
			if ($this->br)
			{
				if (!$this->p)	$this->text=str_replace("\n", "<br />\n", $this->text);
				else
				{
					$this->text=preg_replace('/^([^\n].*?)(?=\n\n)/s', '<p>$1</p>', $this->text);
					$this->text=preg_replace('/(?<=\n\n)([^\n\<].*?)(?=\n\n)/s', '<p>$1</p>', $this->text);
					$this->text=preg_replace('/(?<=\n\n)([^\n\<].*?)$/s', '<p>$1</p>', $this->text);

					$this->text=preg_replace('/([^\n])\n([^\n])/', "$1<br />\n$2", $this->text);
				}
			}
			else
			{
				if ($this->p)
				{
					$this->text=preg_replace('/^([^\n].*?)(?=\n\n)/s', '<p>$1</p>', $this->text);
					$this->text=preg_replace('/(?<=\n\n)([^\n].*?)(?=\n\n)/s', '<p>$1</p>', $this->text);
					$this->text=preg_replace('/(?<=\n\n)([^\n].*?)$/s', '<p>$1</p>', $this->text);
				}
			}
		}
		else
		{
			if ($this->p)	$this->text='<p>'.$this->text.'</p>';
		}
		
		return;
	}
	
	private function delpbr()
	{
		$tags=array('<br />', '<p>', '</p>');
		
		$this->text=str_replace($tags, '', $this->text);
		
		return;
	}
	
	private function spaces()
	{
		$this->text=str_replace("\r", '', $this->text);
		
		if ($this->delTab)	$this->text=str_replace("\t", '', $this->text);
		else if ($this->replaceTab)		$this->text=str_replace("\t", ' ', $this->text);

		if ($this->trim)
		{
			$this->text=trim($this->text);
		}

		$this->text=str_replace('&nbsp;', ' ', $this->text);
		$this->text=str_replace('&#160;', ' ', $this->text);
				
		if ($this->delRepeatSpace)
		{
			$this->text=preg_replace('/ {2,}/', ' ', $this->text);
			$this->text=preg_replace("/\n {1,}/m", "\n", $this->text);
			$this->text=preg_replace("/\n{3,}/m", "\n\n", $this->text);
		}
	
		if ($this->delSpaceBeforePunctuation)
		{
			$before=array('!', ';', ',', '?', '.', ')',);
			$after=array();
			foreach($before as $i)	$after[]='/ \\'.$i.'/';
			$this->text=preg_replace($after, $before, $this->text);
			$this->text=preg_replace('/\( /', '(', $this->text);
		}
		
		if ($this->spaceBeforeParticles)
		{
			$this->text=preg_replace('/ (ли|ль|же|ж|бы|б)(?![а-яА-Я])/', $this->space.'$1', $this->text);
		}
		
		if ($this->spaceAfterShortWord and $this->lengthShortWord>0)
		{
			$this->text=preg_replace('/( [а-яА-Я\w]{1,'.$this->lengthShortWord.'}) /', '$1'.$this->space, $this->text);		
		}
		
		if ($this->spaceBeforeLastWord and $this->lengthLastWord>0)
		{
			$this->text=preg_replace('/ ([а-яА-Я\w]{1,'.$this->lengthLastWord.'})(?=\.|\?|:|\!|,)/', $this->space.'$1', $this->text);		
		}	
			
		if ($this->spaceAfterNum)
		{
			$this->text=preg_replace('/(№|&#8470;)(?=\d)/', $this->number.$this->space, $this->text);
			$this->text=preg_replace('/(§|&#167;|&sect;)(?=\d)/', $this->sect.$this->space, $this->text);			
		}
		
		if ($this->delSpaceBeforeProcent)
		{
			$this->text=preg_replace('/( |&nbsp;|&#160;)%/', '%', $this->text);		
		}
		
		return;
	}

	public function execute($text, $tmode=TYPOGRAF_MODE_DEFAULT, $coding='utf-8')
	{
		if (empty($text))	return '';
		
		$this->text=$text;
		$this->setTMode($tmode);
		
		if ($coding!='windows-1251')	$this->text=iconv($coding, 'windows-1251', $this->text);

		$b=strpos($this->text, '<');
		$e=strpos($this->text, '>');
		if ($b!==false and $e!==false)	$this->_isHTMLCode=true;
		else	$this->_isHTMLCode=false;
		
		if ($this->doReplaceBefore)	$this->replaceBefore();
		
		$this->spaces();
		$this->quotes();
		$this->dashes();

		if ($this->findUrls)	$this->setUrls();

		$this->pbr();
		
		$this->replaceWindowsCodes();

		if ($this->doReplaceAfter)	$this->replaceAfter();
		
		if ($coding!='windows-1251')	$this->text=iconv('windows-1251', $coding, $this->text);
		
		return $this->text;
	}
	
	private function replaceWindowsCodes()
	{
		$after=array('&#167;', '&#169;', '&#174;', '&#8482;', '&#176;', '&#171;', '&#183;',
				'&#187;', '&#133;', '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#164;', '&#166;',
				'&#8222;', '&#8226;', '&#8211;', $this->plusmn, $this->tire, $this->number, '&#8240;',
				'&#8364;', '&#182;', '&#172;');

		$before=array('§', '©',  '®', '™',  '°', '«', '·',
			'»', '…', '‘', '’', '“', '”', '¤', '¦',
			'„', '•', '–', '±', '—', '№', '‰',
			'€', '¶', '¬');

		$this->text=str_replace($before, $after, $this->text);
		
		return;
	}
	
	private function setUrls()
	{
		if ($this->_isHTMLCode)	return;
		
		$prefix='(http|https|ftp|telnet|news|gopher|file|wais)://';
		$pureUrl='([[:alnum:]/\n+-=%&:_.~?]+[#[:alnum:]+]*)';
		$this->text=eregi_replace($prefix.$pureUrl, '<a href="\\1://\\2">\\1://\\2</a>', $this->text); 
		
		return;
	}
}

function typograf($text, $tmode=TYPOGRAF_MODE_DEFAULT, $coding='utf-8')
{
	$typo=new Typograf();

	return $typo->execute($text, $tmode, $coding);
}

?>