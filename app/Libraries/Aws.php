<?php
namespace App\Libraries;
use Aws\Sdk;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class AWS
{
    function getName(){
        $sharedConfig = [
            'profile' => 'default',
            'region' => 'us-east-2',
            'version' => 'latest'
        ];
        return $sharedConfig;
        // Create an SDK class used to share configuration across clients.
        $sdk = new Sdk($sharedConfig);

        // Use an Aws\Sdk class to create the S3Client object.
        $s3Client = $sdk->createS3();

        // Send a PutObject request and get the result object.
        $result = $s3Client->putObject([
            'Bucket' => 'my-bucket',
            'Key' => 'my-key',
            'Body' => 'this is the body!'
        ]);

        // Download the contents of the object.
        $result = $s3Client->getObject([
            'Bucket' => 'my-bucket',
            'Key' => 'my-key'
        ]);

        // Print the body of the result by indexing into the result object.
        echo $result['Body'];
    }
}
?>
