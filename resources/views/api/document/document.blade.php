<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Api接口文档</title>
    <!-- Bootstrap -->
    <link href="/static/api/css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="/static/api/js/html5shiv.min.js"></script>
      <script src="/static/api/js/respond.min.js"></script>
    <![endif]-->
    <script src="/static/api/js/jquery-1.11.3.min.js"></script>
    <script src="/static/api/js/bootstrap.min.js"></script>    
    <style>
      .text_center {
        text-align:center;
      }
      /*请求参数*/
      .request_messge_name_width{
        width:200px;
      }
      .request_param_name{
        width:150px;
      }
      .request_param_type{
        width:150px;
      }
      .request_param_pass{
        width:80px;
      }
      /* 固定菜单接口 */
      .dw{
        position: fixed; 
        top: 0px;
        z-index:999;
        width:inherit;
      }
      .dw_menu{
        width:100%;
        height: 42px;
        padding: 3px;
      }
      .dw_content {
        max-height:800px;
        overflow:auto;
      }
      /*返回顶部*/
      .top{
        position: fixed; 
        z-index:999;
        bottom:60px;
        cursor:pointer;
      }
    </style>
  </head>
  <body>

<div class="container-fluid">
  <div class="row">
      <!-- 主体 -->
      <div class="col-lg-7 col-lg-offset-1 col-md-7 col-md-offset-1">
      @foreach ($data as $key => $value)
      <div class="row" id="{{ $key + 1 }}">
          <h2><b>{{ $key + 1 }} {{ $value['name'] }}</b></h2>

          <!-- 请求信息 -->
          <p>请求信息</p>
          <table class="table table-bordered">
            <tbody>
              <tr>
                <td class="request_messge_name_width">请求信息</td>
                <td>{{ $value['url'] }}</td>
              </tr>
              <tr>
                <td class="request_messge_name_width">请求方法</td>
                <td>{{ $value['method'] }}</td>
              </tr>
            </tbody>
          </table>

          <!-- 请求参数 -->
          @if ($value['param'])
          <p>请求参数</p>
          <table class="table table-bordered">
            <thead>
              <td class="request_param_name">参数名</td>
              <td class="request_param_type">类型</td>
              <td class="request_param_pass">是否必传</td>
              <td>描述</td>
            </thead>
            <tbody>
              @foreach ($value['param'] as $param)
              <tr>
                <td>{{ $param['param'] }}</td>
                <td>{{ $param['type'] }}</td>
                <td>
                  @if ($param['pass'] == 'R')
                    是
                  @elseif($param['pass'] == 'O')
                    否
                  @endif
                </td>
                <td>{{ $param['bewrite'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        @endif

          <!-- 返回信息 -->
          @if ($value['response'])
          <p>返回信息</p>
          <table class="table table-bordered">
            <thead>
              <td class="request_param_name">参数名</td>
              <td class="request_param_type">类型</td>
              <td>描述</td>
            </thead>
            <tbody>
              @foreach ($value['response'] as $response)
              <tr>
                <td>{{ $response['param'] }}</td>
                <td>{{ $response['type'] }}</td>
                <td>{{ $response['bewrite'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif

          <!-- 返回参数 -->
          @if ($value['field'])
          <p>返回参数</p>
          <table class="table table-bordered">
          <thead>
              <td class="request_param_name">参数名</td>
              <td class="request_param_type">类型</td>
              <td>描述</td>
            </thead>
            <tbody>
              @foreach ($value['field'] as $field)
              <tr>
                <td>{{ $field['param'] }}</td>
                <td>{{ $field['type'] }}</td>
                <td>{{ $field['bewrite'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif

          <!-- json -->
          @if ($value['returnjson'])
          <p>返回JSON样例</p>
          @foreach ($value['returnjson'] as $returnjson)
            <pre>{{ $returnjson }}</pre>
          @endforeach
          @endif

          <!-- string -->
          @if ($value['returnstring'])
          <p>返回String样例</p>
          @foreach ($value['returnstring'] as $returnstring)
            <pre>{{ $returnstring }}</pre>
          @endforeach
          @endif

      </div>
      @endforeach

      <!-- 主体结束 -->
      </div>


        <!-- 菜单 -->
      <div class="col-lg-3 col-md-3">

        <div class="list-group dw">
        <!-- 按钮组 -->
        <div class="dw_menu btn-primary">
              @foreach ($array['version'] as $value)
              <a href="?version={{ $value }}" class="btn @if ($value == $version) btn-warning @else @endif btn-default active" role="button">{{ $value }}</a> &nbsp;&nbsp;
              @endforeach
        </div>

        <ul class="list-group dw_content">
        @foreach ($array['title'] as $value)
          <li class="list-group-item list-group-item-success text_center">
            {{ $value }}
          </li>
            <div>
              @foreach ($data as $key => $v)
                @if ($value == $v['title'])
                  <a href="#{{ $key + 1 }}" class="list-group-item">{{ $key + 1 }} {{ $v['name'] }}</a>
                @endif
              @endforeach
            </div>
          @endforeach
        </ul>

        </div>
      </div>

      <!-- 返回顶部 -->
      <div class="col-lg-1 col-md-1">
            <a href="javascript:scrollTo(0,0);"><img width="29" height="65" src="/static/api/img/top.gif" class="top"></a>
      </div>

  </div>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>

  </body>
</html>