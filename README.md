# woconv

woconv是一个简单的视频转码工具，基于libav项目中的avconv命令。

使用方法：

    woconv.rb video_name.mov

将会转换生成一个叫做`video_name.mp4`的视频文件。

你也可以手动指定输出的mp4文件的名字。

    woconv.rb video_name.mov output_video_name.mp4
