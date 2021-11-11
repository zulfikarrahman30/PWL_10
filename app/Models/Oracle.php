<?php

namespace App\Models;
use Carbon\Carbon;
use DB;
class Oracle
{

function get_oracle_client($endpoint)
{
	$ORACLE_ACCESS_KEY='c68210f5b01d6caad9b2368cda37f44eb69d7352';
	$ORACLE_SECRET_KEY='ouIO989QPJj5xee+iFBYRby0F2hGReWN/koWc2+8UsI=';
	$ORACLE_REGION='ap-sydney-1';
	$ORACLE_NAMESPACE='sddme9alipvf';

    $endpoint = "https://".$ORACLE_NAMESPACE.".compat.objectstorage.".$ORACLE_REGION.".oraclecloud.com/{$endpoint}";

    return new \Aws\S3\S3Client(array(
        'credentials' => [
            'key' => $ORACLE_ACCESS_KEY,
            'secret' => $ORACLE_SECRET_KEY,
        ],
        'version' => 'latest',
        'region' => $ORACLE_REGION,
        'bucket_endpoint' => true,
        'endpoint' => $endpoint
    ));
}

function upload_file_oracle($bucket_name, $folder_name = '', $file_name)
{
	$ORACLE_ACCESS_KEY='c68210f5b01d6caad9b2368cda37f44eb69d7352';
	$ORACLE_SECRET_KEY='ouIO989QPJj5xee+iFBYRby0F2hGReWN/koWc2+8UsI=';
	$ORACLE_REGION='ap-sydney-1';
	$ORACLE_NAMESPACE='sddme9alipvf';

    if (empty(trim($bucket_name))) {
        return array('success' => false, 'message' => 'Please provide valid bucket name!');
    }

    if (empty(trim($file_name))) {
        return array('success' => false, 'message' => 'Please provide valid file name!');
    }

    if ($folder_name !== '') {
        $keyname = $folder_name . '/' . $file_name;
        $endpoint =  "{$bucket_name}/";
    } else {
        $keyname = $file_name;
        $endpoint =  "{$bucket_name}/{$keyname}";
    }

   
    $s3 = $this->get_oracle_client($endpoint);
    $s3->getEndpoint();
    
    $file_url = "https://objectstorage.ap-sydney-1.oraclecloud.com/p/ftZBU3hM5Ti1B7juoNqMBRkqcLWj_7-oh6r--6nMt0ApcmlNjUomv4DfqmGxn0wf/n/sddme9alipvf/b/bucket-20211112-0010/o/{$keyname}";
    try {
        $s3->putObject(array(
            'Bucket' => $bucket_name,
            'Key' => $keyname,
            'SourceFile' => $file_name,
            'StorageClass' => 'REDUCED_REDUNDANCY'
        ));

        return array('success' => true, 'message' => $file_url);
    } catch (S3Exception $e) {
        return array('success' => false, 'message' => $e->getMessage());
    } catch (Exception $e) {
        return array('success' => false, 'message' => $e->getMessage());
    }
}

	public function upFileOracle($file_name)
	{
		$bucket_name='bucket-20211112-0010';
		$folder_name='uts';
		$upload = $this->upload_file_oracle($bucket_name, $folder_name, $file_name);
		return $upload;
	}
}