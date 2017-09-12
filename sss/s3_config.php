<?php
// Bucket Name
$bucket="testorsss";
if (!class_exists('S3'))require_once('S3.php');
			
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJLV6DIJLVNQFOYNA');

if (!defined('awsSecretKey')) define('awsSecretKey', '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');
			
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);

$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);

?>