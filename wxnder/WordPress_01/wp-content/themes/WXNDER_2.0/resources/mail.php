<?php
function bestelBevestiging($email , $naam, $id)
{
    //The url you wish to send the POST request to
  $url = "https://api.mailersend.com/v1/email";

  //The data you want to send via POST
  $fields = [
    "from" => [
      "email" => "besteld@wxnder.nl",
      "name"=> "WXNDER",
    ],
    "to"=> [
      [
        "email"=> $email,
        "name"=> $naam,
      ]
    ],
    "subject"=> "WXNDER Bestelbevestiging",
    "template_id"=> 'zr6ke4nw834on12w',
    "variables"=> [
      [
        "email"=> $email,
        "substitutions"=> [
          [
            "var"=> "Name",
            "value"=> $naam,
          ],
          [
            "var"=>'name',
            'value'=> $naam,
          ],
          [
            "var"=>'id',
            'value'=> $id,
          ]
        ]
      ]
    ]
];

  //url-ify the data for the POST
  $fields_string = http_build_query($fields);

  //open connection
  $ch = curl_init();

  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYmE3MzIxOTg1Y2FiYzZhNGVjZGQ3MTUwODBmYTBkYzBiZDM5MDQ5NDNlZGVmZmVlNTczMjc4ZGI3MjkwYjAwZTUxNDc5ZTRjYTg1ZmZiZTQiLCJpYXQiOjE2MTM0Nzg4MzMsIm5iZiI6MTYxMzQ3ODgzMywiZXhwIjo0NzY5MTUyNDMzLCJzdWIiOiIyMjAxIiwic2NvcGVzIjpbImVtYWlsX2Z1bGwiLCJkb21haW5zX2Z1bGwiLCJhY3Rpdml0eV9mdWxsIiwiYW5hbHl0aWNzX2Z1bGwiLCJ0b2tlbnNfZnVsbCIsIndlYmhvb2tzX2Z1bGwiLCJ0ZW1wbGF0ZXNfZnVsbCJdfQ.VVMoFXU1FwpH3mT4Kd7XwlkSPdOrURJcynD5CNrz7NMt7dsKXrUCabiUSxJSL-YCNbH1Hq1BBtFd-IIBkbtXdPgtAS_cTiyJfnJzbCTDyFMSQQXtG974PJfWDzt-C9lD_s3qlrZHQ9GNOwLOn_fBUlvsjSYtphAZ6xIDK8-5I-Rb15q1DDgnYjkE1wD3vQwP345KC-h6QURmkAvqzImmJc9yw0hJpkmbOnNMGdmYt54E__ZLaetzzJilf44ZHPrjKEchmA1b6IhB2twBguN1nfW3wC8iDoPKIGZfECZvIoLRGdDQc06eBWeHm8AL9hkr15-3fsznz-MwJKPm8ePWsrv5_WfJOkS5b5lNzTZthxoKXt5iCzLVr1rXr8BzYvgk278vwXWAyJZUaIN2-pO4vGrmEVWi4-CmQshQ2Zbi-XwKv0WBN5SfOjW5C6NoCEQdN_GvCUsiMG_kQQYLQkU9z_V6SddQozscN0IlIOATKD2LXe1a7qlt-NQdfIpMf_VBpD6u7SBYysr4r-3z529XAbW4UG_DXnmCIph1ouezxN9OWA1_Ca-vp0vWUkH5BohOAbyKVWstk1tgxZJb5GIbwTyvgfN8irPXEwp7YOCGYAJnadIVAjHqx0SmA7399RLj56zap4_TrBBjAfFxxtjvmuGVMi4D-WGDbILQxUDDUXA',
));

  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

  //execute post
  $result = curl_exec($ch);
  return $result;
}
function bestelBevestigingIntern($email , $naam, $id, $product, $prijs, $adres, $info)
{
    //The url you wish to send the POST request to
  $url = "https://api.mailersend.com/v1/email";

  //The data you want to send via POST
  $fields = [
    "from" => [
      "email" => "besteld@wxnder.nl",
      "name"=> "WXNDER",
    ],
    "to"=> [
      [
        "email"=> "info@wxnder.nl",
        "name"=> "wxnder-boys",
      ]
    ],
    "subject"=> "Nieuwe bestelling",
    "template_id"=> 'z86org83314ew137',
    "variables"=> [
      [
        "email"=> "info@wxnder.nl",
        "substitutions"=> [
          [
            "var"=> "naam",
            "value"=> $naam,
          ],
          [
            "var"=>'ordern',
            'value'=> $id,
          ],
          [
            "var"=>'product',
            'value'=> $product,
          ],
          [
            "var"=>'prijs',
            'value'=> $prijs,
          ],
          [
            "var"=>'adres',
            'value'=> $adres,
          ],
          [
            "var"=>'email',
            'value'=> $email,
          ],
          [
            "var"=>'info',
            'value'=> $info,
          ]
        ]

      ]
    ]
];

  //url-ify the data for the POST
  $fields_string = http_build_query($fields);

  //open connection
  $ch = curl_init();

  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYmE3MzIxOTg1Y2FiYzZhNGVjZGQ3MTUwODBmYTBkYzBiZDM5MDQ5NDNlZGVmZmVlNTczMjc4ZGI3MjkwYjAwZTUxNDc5ZTRjYTg1ZmZiZTQiLCJpYXQiOjE2MTM0Nzg4MzMsIm5iZiI6MTYxMzQ3ODgzMywiZXhwIjo0NzY5MTUyNDMzLCJzdWIiOiIyMjAxIiwic2NvcGVzIjpbImVtYWlsX2Z1bGwiLCJkb21haW5zX2Z1bGwiLCJhY3Rpdml0eV9mdWxsIiwiYW5hbHl0aWNzX2Z1bGwiLCJ0b2tlbnNfZnVsbCIsIndlYmhvb2tzX2Z1bGwiLCJ0ZW1wbGF0ZXNfZnVsbCJdfQ.VVMoFXU1FwpH3mT4Kd7XwlkSPdOrURJcynD5CNrz7NMt7dsKXrUCabiUSxJSL-YCNbH1Hq1BBtFd-IIBkbtXdPgtAS_cTiyJfnJzbCTDyFMSQQXtG974PJfWDzt-C9lD_s3qlrZHQ9GNOwLOn_fBUlvsjSYtphAZ6xIDK8-5I-Rb15q1DDgnYjkE1wD3vQwP345KC-h6QURmkAvqzImmJc9yw0hJpkmbOnNMGdmYt54E__ZLaetzzJilf44ZHPrjKEchmA1b6IhB2twBguN1nfW3wC8iDoPKIGZfECZvIoLRGdDQc06eBWeHm8AL9hkr15-3fsznz-MwJKPm8ePWsrv5_WfJOkS5b5lNzTZthxoKXt5iCzLVr1rXr8BzYvgk278vwXWAyJZUaIN2-pO4vGrmEVWi4-CmQshQ2Zbi-XwKv0WBN5SfOjW5C6NoCEQdN_GvCUsiMG_kQQYLQkU9z_V6SddQozscN0IlIOATKD2LXe1a7qlt-NQdfIpMf_VBpD6u7SBYysr4r-3z529XAbW4UG_DXnmCIph1ouezxN9OWA1_Ca-vp0vWUkH5BohOAbyKVWstk1tgxZJb5GIbwTyvgfN8irPXEwp7YOCGYAJnadIVAjHqx0SmA7399RLj56zap4_TrBBjAfFxxtjvmuGVMi4D-WGDbILQxUDDUXA',
));

  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

  //execute post
  $result = curl_exec($ch);
  return $result;
}
function leverBevestiging($email , $naam, $id, $levertijd)
{
    //The url you wish to send the POST request to
  $url = "https://api.mailersend.com/v1/email";

  //The data you want to send via POST
  $fields = [
    "from" => [
      "email" => "besteld@wxnder.nl",
      "name"=> "WXNDER",
    ],
    "to"=> [
      [
        "email"=> $email,
        "name"=> $naam,
      ]
    ],
    "subject"=> "Levering WXNDER",
    "template_id"=> 'ynrw7gyrq2l2k8e3',
    "variables"=> [
      [
        "email"=> $email,
        "substitutions"=> [
          [
            "var"=> "Name",
            "value"=> $naam,
          ],
          [
            "var"=>'naam',
            'value'=> $naam,
          ],
          [
            "var"=>'id',
            'value'=> $id,
          ],
          [
            "var"=>'date',
            'value'=> $levertijd,
          ]
        ]
      ]
    ]
];

  //url-ify the data for the POST
  $fields_string = http_build_query($fields);

  //open connection
  $ch = curl_init();

  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYmE3MzIxOTg1Y2FiYzZhNGVjZGQ3MTUwODBmYTBkYzBiZDM5MDQ5NDNlZGVmZmVlNTczMjc4ZGI3MjkwYjAwZTUxNDc5ZTRjYTg1ZmZiZTQiLCJpYXQiOjE2MTM0Nzg4MzMsIm5iZiI6MTYxMzQ3ODgzMywiZXhwIjo0NzY5MTUyNDMzLCJzdWIiOiIyMjAxIiwic2NvcGVzIjpbImVtYWlsX2Z1bGwiLCJkb21haW5zX2Z1bGwiLCJhY3Rpdml0eV9mdWxsIiwiYW5hbHl0aWNzX2Z1bGwiLCJ0b2tlbnNfZnVsbCIsIndlYmhvb2tzX2Z1bGwiLCJ0ZW1wbGF0ZXNfZnVsbCJdfQ.VVMoFXU1FwpH3mT4Kd7XwlkSPdOrURJcynD5CNrz7NMt7dsKXrUCabiUSxJSL-YCNbH1Hq1BBtFd-IIBkbtXdPgtAS_cTiyJfnJzbCTDyFMSQQXtG974PJfWDzt-C9lD_s3qlrZHQ9GNOwLOn_fBUlvsjSYtphAZ6xIDK8-5I-Rb15q1DDgnYjkE1wD3vQwP345KC-h6QURmkAvqzImmJc9yw0hJpkmbOnNMGdmYt54E__ZLaetzzJilf44ZHPrjKEchmA1b6IhB2twBguN1nfW3wC8iDoPKIGZfECZvIoLRGdDQc06eBWeHm8AL9hkr15-3fsznz-MwJKPm8ePWsrv5_WfJOkS5b5lNzTZthxoKXt5iCzLVr1rXr8BzYvgk278vwXWAyJZUaIN2-pO4vGrmEVWi4-CmQshQ2Zbi-XwKv0WBN5SfOjW5C6NoCEQdN_GvCUsiMG_kQQYLQkU9z_V6SddQozscN0IlIOATKD2LXe1a7qlt-NQdfIpMf_VBpD6u7SBYysr4r-3z529XAbW4UG_DXnmCIph1ouezxN9OWA1_Ca-vp0vWUkH5BohOAbyKVWstk1tgxZJb5GIbwTyvgfN8irPXEwp7YOCGYAJnadIVAjHqx0SmA7399RLj56zap4_TrBBjAfFxxtjvmuGVMi4D-WGDbILQxUDDUXA',
));

  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

  //execute post
  $result = curl_exec($ch);
  return $result;
}
function leverBevestigingPOSTNL($email , $naam, $id, $trackAndTrace, $postcode)
{
    //The url you wish to send the POST request to
  $url = "https://api.mailersend.com/v1/email";

  //The data you want to send via POST
  $fields = [
    "from" => [
      "email" => "besteld@wxnder.nl",
      "name"=> "WXNDER",
    ],
    "to"=> [
      [
        "email"=> $email,
        "name"=> $naam,
      ]
    ],
    "subject"=> "Levering WXNDER",
    "template_id"=> 'pq3enl6njm42vwrz',
    "variables"=> [
      [
        "email"=> $email,
        "substitutions"=> [
          [
            "var"=> "Name",
            "value"=> $naam,
          ],
          [
            "var"=>'naam',
            'value'=> $naam,
          ],
          [
            "var"=>'id',
            'value'=> $id,
          ],
          [
            "var"=>'tracktracecode',
            'value'=> $trackAndTrace,
          ],
          [
            "var"=>'tracktraceurl',
            'value'=> "https://jouw.postnl.nl/track-and-trace/$trackAndTrace-NL-$postcode",
          ]
        ]
      ]
    ]
];

  //url-ify the data for the POST
  $fields_string = http_build_query($fields);

  //open connection
  $ch = curl_init();

  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYmE3MzIxOTg1Y2FiYzZhNGVjZGQ3MTUwODBmYTBkYzBiZDM5MDQ5NDNlZGVmZmVlNTczMjc4ZGI3MjkwYjAwZTUxNDc5ZTRjYTg1ZmZiZTQiLCJpYXQiOjE2MTM0Nzg4MzMsIm5iZiI6MTYxMzQ3ODgzMywiZXhwIjo0NzY5MTUyNDMzLCJzdWIiOiIyMjAxIiwic2NvcGVzIjpbImVtYWlsX2Z1bGwiLCJkb21haW5zX2Z1bGwiLCJhY3Rpdml0eV9mdWxsIiwiYW5hbHl0aWNzX2Z1bGwiLCJ0b2tlbnNfZnVsbCIsIndlYmhvb2tzX2Z1bGwiLCJ0ZW1wbGF0ZXNfZnVsbCJdfQ.VVMoFXU1FwpH3mT4Kd7XwlkSPdOrURJcynD5CNrz7NMt7dsKXrUCabiUSxJSL-YCNbH1Hq1BBtFd-IIBkbtXdPgtAS_cTiyJfnJzbCTDyFMSQQXtG974PJfWDzt-C9lD_s3qlrZHQ9GNOwLOn_fBUlvsjSYtphAZ6xIDK8-5I-Rb15q1DDgnYjkE1wD3vQwP345KC-h6QURmkAvqzImmJc9yw0hJpkmbOnNMGdmYt54E__ZLaetzzJilf44ZHPrjKEchmA1b6IhB2twBguN1nfW3wC8iDoPKIGZfECZvIoLRGdDQc06eBWeHm8AL9hkr15-3fsznz-MwJKPm8ePWsrv5_WfJOkS5b5lNzTZthxoKXt5iCzLVr1rXr8BzYvgk278vwXWAyJZUaIN2-pO4vGrmEVWi4-CmQshQ2Zbi-XwKv0WBN5SfOjW5C6NoCEQdN_GvCUsiMG_kQQYLQkU9z_V6SddQozscN0IlIOATKD2LXe1a7qlt-NQdfIpMf_VBpD6u7SBYysr4r-3z529XAbW4UG_DXnmCIph1ouezxN9OWA1_Ca-vp0vWUkH5BohOAbyKVWstk1tgxZJb5GIbwTyvgfN8irPXEwp7YOCGYAJnadIVAjHqx0SmA7399RLj56zap4_TrBBjAfFxxtjvmuGVMi4D-WGDbILQxUDDUXA',
));

  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

  //execute post
  $result = curl_exec($ch);
  return $result;
}
 ?>
