<?php
/**
* $Id$
*
* S3 form upload example
*/

if (!class_exists('S3')) require_once 'S3.php';

// AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJLV6DIJLVNQFOYNA');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');

// Check for CURL
if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
	exit("\nERROR: CURL extension not loaded\n\n");

// Pointless without your keys!
/*if (awsAccessKey == 'AKIAJLV6DIJLVNQFOYNA' || awsSecretKey == '16xtQPDZ2n8CGKY7ElRPFcKVyEhZBVJfA6YP/mhb')
	exit("\nERROR: AWS access information required\n\nPlease edit the following lines in this file:\n\n".
	"define('awsAccessKey', 'AKIAJLV6DIJLVNQFOYNA');\ndefine('awsSecretKey', '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');\n\n");*/


S3::setAuth(awsAccessKey, awsSecretKey);

$bucket = 'testorsss';
$path = 'myfiles/'; // Can be empty ''

$lifetime = 3600; // Period for which the parameters are valid
$maxFileSize = (1024 * 1024 * 50); // 50 MB

$metaHeaders = array('uid' => 123);
$requestHeaders = array(
    'Content-Type' => 'application/octet-stream',
    'Content-Disposition' => 'attachment; filename=${filename}'
);

$params = S3::getHttpUploadPostParams(
    $bucket,
    $path,
    S3::ACL_PUBLIC_READ,
    $lifetime,
    $maxFileSize,
    201, // Or a URL to redirect to on success
    $metaHeaders,
    $requestHeaders,
    false // False since we're not using flash
);

$uploadURL = 'https://' . $bucket . '.s3.amazonaws.com/';

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>S3 Form Upload</title>
</head>
<body>
    <form method="post" action="<?php echo $uploadURL; ?>" enctype="multipart/form-data">
<?php
    foreach ($params as $p => $v)
        echo "        <input type=\"hidden\" name=\"{$p}\" value=\"{$v}\" />\n";
?>
        <input type="file" name="file" />&#160;<input type="submit" value="Upload" />
    </form>
    
    <?php
	
	/*S3::putBucket("www.aurotest.com", S3::ACL_PUBLIC_READ);*/
	
	/*S3::deleteBucket("www.aurotest.com");*/
	
	/*S3::deleteObject("www.aurotest.com");*/
	?>
    
    
    <?php
	
	// Get the contents of our bucket
	/*$contents = S3::getBucket("testorsss");
	foreach ($contents as $file){
	
		$fname = $file['name'];
		$furl = "http://testorssst.s3.amazonaws.com/".$fname;
		
		//output a link to the file
		echo "<a href=\"$furl\">$fname</a><br />";
	}*/
	/*S3::copyObject( "testorsss", "man.jpg", "www.aurotest.com", "man.jpg");*/
	/*$contents = S3::getBucket("www.aurotest.com");
	foreach ($contents as $file){
	
		$fname = $file['name'];
		S3::deleteObject("www.aurotest.com",$fname);
		
	}*/
	
	
	$contents = S3::getBucket("testorsss");
	foreach ($contents as $file){
	
		$fname = $file['name'];
		S3::copyObject("testorsss",$fname,"www.aurotest.com",$fname);
		
	}
	
	?>
    
</body>
</html>
