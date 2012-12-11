#!/usr/bin/env ruby

# This is video convert tools for woShare.
# It's based on libav (http://libav.org)
#
# Author: chenxin <chenxin@smapp.hk>
# Date:   2012-12-06


# Path for avconv executable
$avconv_exec = "avconv"

# parameters for avconv
$avconv_param = "-vcodec libx264 -acodec aac -strict experimental -y"

# Read command line parameters
$input_file = ARGV.shift
$output_file = ARGV.shift

# if input_file is not specified, print help message and quit
if $input_file.nil?
  puts "Usage: #{__FILE__} input-file-name [output-file-name]"
  exit 0
end

# If output_file is not specified, use default name pattern.
$output_file = $input_file.sub(/\.[\w]+$/, '.mp4') if $output_file.nil?

# Execute avconv to conver
puts "-> Convert #{$input_file} to #{$output_file}"

ret = system "#{$avconv_exec} -i #{$input_file} #{$avconv_param} #{$output_file}"

if ret
  puts "-> Convert Successful!"
else
  puts "-> Convert Failed!"
end
