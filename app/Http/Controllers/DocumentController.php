<?php

namespace App\Http\Controllers;

# 文档
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    # 版本
    private $version = ['1.0'];

    # 页面展示的数据
    private $data = [];

    # 文档文件
    private $file = [];

    # 忽略文件
    private $ignore = ['DocumentController.php', 'Controller.php'];

    # 文档列表
    public function document(Request $request)
    {
        $input['version'] = $request->get('version', $this->version);
        # 目录下文件列表
        $this->getAllFiles(app_path() . '/Http/Controllers', $this->file);
        # 数据解析
        $this->data();
        # 数据展示处理，版本与标题
        $array = $this->method([
            'version' => $input['version']
        ]);
        # 数据为空或者数据错误时
        if (empty($input['version']) || !in_array($input['version'], $array['version'])) {
            $input['version'] = $array['version'][0];
        }

        return view('api.document.document', [
            'data' => $this->data,
            'array' => $array,
            'version' => $input['version'],
        ]);
    }

    # 获取要生成文档的文件
    private function getAllFiles($path,&$files)
    {
        if (is_dir($path)) {
            $dir = dir($path);
            while ($file = $dir ->read()) {
                if ($file !== "." && $file !== "..") {
                    !in_array($file, $this->ignore) && $this->getAllFiles($path . "/" . $file, $files);
                }
            }
            $dir ->close();
        }
        if (is_file($path)) {
            $files[] = $path;
        }
    }

    # 文件解析
    private function data()
    {
        foreach ($this->file as $value) {
            $file = file_get_contents($value);
            $this->block($file);
        }
    }

    # 文件区块匹配
    private function block($file)
    {
        preg_match_all("/\/\*\*(.*?)\*\//is", $file, $html);
        if ($html[1]) {
            foreach ($html[1] as $value) {
                $this->analyze($value); // 区块解析
            }
        }
    }

    # 区块解析
    private function analyze($html)
    {
        # 参数初始化
        $data = [
            'url' => '', // 接口信息
            'version' => '2.0', // 默认版本
            'method' => '', // 方法
            'title' => '其它', // 标题
            'name' => '', // 接口名
            'param' => [], // 请求参数
            'response' => [], // 返回信息
            'field' => [], // 返回参数
            'returnjson' => [], // 返回示例 json
            'returnstring' => [] // 返回示例字符串
        ];

        $array = explode(PHP_EOL, $html);
        if (empty($array)) {
            return false;
        }

        foreach ($array as $v) {
            # url
            if (preg_match("/\s+\*\s@url\s+(.*)/is", $v, $temp)) {
                $data['url'] = trim($temp[1]);
            }
            # version
            if (preg_match("/\s+\*\s@version\s+(.*)/is", $v, $temp)) {
                $data['version'] = trim($temp[1]);
            }
            # method
            if (preg_match("/\s+\*\s@method\s+(.*)/is", $v, $temp)) {
                $data['method'] = trim($temp[1]);
            }
            # name
            if (preg_match("/\s+\*\s@name\s+(.*)/is", $v, $temp)) {
                $data['name'] = trim($temp[1]);
                # title 标题，中文和英文标点
                if (preg_match("/(.*)[|~-~-]+(.*)/is", $temp[1], $temp)) {
                    $data['title'] = trim($temp[1]);
                    $data['name'] = trim($temp[2]);
                }
            }
            # param
            if (preg_match("/\s+\*\s@param\s+(.*)/is", $v, $temp)) {
                $temp = $this->analyze_param(trim($temp[1]));
                if ($temp) {
                    $data['param'][] = $temp;
                }
            }
            # response
            if (preg_match("/\s+\*\s@response\s+(.*)/is", $v, $temp)) {
                $temp = $this->analyze_response_field(trim($temp[1]));
                if ($temp) {
                    $data['response'][] = $temp;
                }
            }
            # field
            if (preg_match("/\s+\*\s@field\s+(.*)/is", $v, $temp)) {
                $temp = $this->analyze_response_field(trim($temp[1]));  // 与 response 格式相同
                if ($temp) {
                    $data['field'][] = $temp;
                }
            }
            # returnjson
            if (preg_match("/\s+\*\s@returnjson\s+(.*)/is", $v, $temp)) {
                $temp = $this->analyze_returnjson(trim($temp[1]));  // json 格式化
                if ($temp) {
                    $data['returnjson'][] = $temp;
                }
            }
            # returnstring
            if (preg_match("/\s+\*\s@returnstring\s+(.*)/is", $v, $temp)) {
                $temp = trim($temp[1]);
                if ($temp) {
                    $data['returnstring'][] = $temp;
                }
            }
        }

        # 必备参数
        if ($data['url'] && $data['method'] && $data['name']) {
            $this->data[] = $data;
        }
    }

    # 请求参数解析
    private function analyze_param($data)
    {
        $array = [
            'type' => '',// 类型
            'param' => '', // 参数
            'pass' => 'R',// 是否必传
            'bewrite' => '', // 描述
        ];

        # 必传
        if (strpos($data, 'R|')) {
            $temp = explode('R|', $data);
            $array['pass'] = 'R';
        }
        # 非必传
        if (strpos($data, 'O|')) {
            $temp = explode('O|', $data);
            $array['pass'] = 'O';
        }
        if (empty($temp)) { // 未匹配到字符串
            return false;
        }
        # 切割前后字符串处理
        $array['bewrite'] = $temp[1];

        $temp = trim(preg_replace("/\s(?=\s)/", "\\1", $temp[0]));  // 多个空格合并为一个
        $temp = explode(" ", $temp);
        if (count($temp) != 2) {
            return false;
        }
        $array['type'] = $temp[0];
        $array['param'] = $temp[1];

        return $array;
    }

    # 返回信息解析
    private function analyze_response_field($data)
    {
        $array = [
            'type' => '',// 类型
            'param' => '', // 参数
            'bewrite' => '', // 描述
        ];
        $temp = trim(preg_replace("/\s(?=\s)/", "\\1", $data));  // 多个空格合并为一个
        $temp = explode(" ", $temp);
        if (count($temp) < 3) { // 参数数量不正确
            return false;
        }
        $array['type'] = $temp[0]; // type 赋值
        $array['param'] = $temp[1]; // param 赋值

        $strpos = strpos($data, $temp[1]) + mb_strlen($temp[1], 'UTF-8'); // 第二个参数最后一次出现的位置
        $array['bewrite'] = trim(mb_substr($data, $strpos)); // 备注赋值

        return $array;
    }

    /* Json数据格式化
    * @param  Mixed  $data   数据
    * @param  String $indent 缩进字符，默认4个空格
    * @return JSON
    */
    private function analyze_returnjson($data, $indent = null)
    {
        // 是否为正常的json格式
        json_decode($data);
        if (json_last_error() != 0) {
            return false;
        }

        // json encode
        // $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        // 将urlencode的内容进行urldecode
        $data = urldecode($data);
        # 缩进处理
        $ret = '';
        $pos = 0;
        $length = strlen($data);
        $indent = isset($indent) ? $indent : '    ';
        $newline = "\n";
        $prevchar = '';
        $outofquotes = true;
        for ($i = 0; $i <= $length; $i++) {
            $char = substr($data, $i, 1);
            if ($char == '"' && $prevchar != '\\') {
                $outofquotes = !$outofquotes;
            } elseif (($char == '}' || $char == ']') && $outofquotes) {
                $ret .= $newline;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $ret .= $indent;
                }
            }
            $ret .= $char;
            if (($char == ',' || $char == '{' || $char == '[') && $outofquotes) {
                $ret .= $newline;
                if ($char == '{' || $char == '[') {
                    $pos++;
                }
                for ($j = 0; $j < $pos; $j++) {
                    $ret .= $indent;
                }
            }
            $prevchar = $char;
        }
        return $ret;
    }

    # 数据展示处理，版本与标题
    public function method($input)
    {
        # 初始化
        $data = $title = $version = [];
        $pattern = '/^([\d.]+)(.*)/is';
        # 获取版本列表，标题列表
        foreach ($this->data as $key => $value) {
            if (preg_match($pattern, $value['version'], $temp)) {
                if (!$temp[2]) { // 版本列表
                    $version[$value['version']] = [];
                }
            }
        }
        $version = array_keys($version); // 版本列表
        sort($version, SORT_NUMERIC);
        $version = array_reverse($version); // 排序
        # 判断输入版本号，强制符合要求
        if (!in_array($input['version'], $version)) {
            $input['version'] = max($version);
        }

        # 格式版本数据，某版本下所有参数
        foreach ($this->data as $key => $value) {
            if (preg_match($pattern, $value['version'], $temp)) {
                if ($temp[2]) { // 版本废弃接口
                    $value['version'] = $temp[1]; // 处理版本号
                    if ($value['version'] < $input['version']) {
                        continue; // 跳过低版本废弃接口
                    }
                } else {
                    // 跳过高于当前版本
                    if ($value['version'] > $input['version']) {
                        continue;
                    }
                }
                $title[$value['title']] = []; // 标题列表
                $value['version'] = $input['version']; // 统一为当前输入的版本号
                $data[] = $value; // 处理符合版本的数据
            }
        }
        $this->data = $data;
        $title = array_keys($title); // 标题列表
        $version = array_slice($version, 0, 5); // 只取前5个

        return [
            'version' => $version,
            'title' => $title
        ];
    }

}