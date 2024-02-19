<!DOCTYPE html>
<html>
<head>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            padding-top: 20px;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: left;
            background-color: #5e54c966;
            color: black;
        }


        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Float four columns side by side */
        .column {
            float: left;
            width: 30%;
            padding: 0 10px;
        }

        /* Remove extra left and right margins, due to padding */
        .row {
            margin: 0 -5px;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        @media screen and (max-width: 600px) {
            .column {
                width: 100%;
                display: block;
                margin-bottom: 20px;
            }
        }

        /* Style the counter cards */
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            padding: 16px;
            text-align: center;
            background-color: #f1f1f1;
        }

        .logoname {
            display: flex;
        }

    </style>
</head>
<body>
  
    <div class="logoname">
        <div>
            <h4 style="text-align:center;color:red">SALES REPORT</h4>
        </div>
    </div>
    <table id="customers">
       
            <tr>
                <th style="background-color: #fff;font-size:14px;border:1px #fff">From Date : {{date('d-m-Y', strtotime($fromdate))}}</th>
            </tr>
      </table>
   
   


    <table id="customers">
        <thead style="background: #5e54c966">
            <tr>
               <th style="font-size:14px;">Branch</th>
               <th style="font-size:14px;">Customer</th>
               <th style="font-size:14px;">BillNo</th>
               <th style="font-size:14px;">Particulars</th>
               <th style="font-size:14px;">Debit</th>
               <th style="font-size:14px;">Credit</th>
               <th style="font-size:14px;">Total</th>
            </tr>
        </thead>
        <tbody id="customer_index">
            @foreach ($Sales_data as $keydata => $Sales_datas)
            <tr>
                  <td style="font-size:14px;">{{ $Sales_datas['branch_name'] }}</td>
                  <td style="font-size:14px;">{{ $Sales_datas['customer_name'] }}</td>
                  <td style="font-size:14px;">{{ $Sales_datas['bill_no'] }}</td>
                  <td style="font-size:14px;">
                  @if ($Sales_datas['type'] == 'SALES')
                                                    @foreach ($Sales_datas['sales_terms'] as $index => $terms_array)
                                                    @if ($terms_array['sales_id'] == $Sales_datas['id'])
                                                    {{ $terms_array['product_name'] }} - {{ $terms_array['kgs'] }}{{ $terms_array['bag'] }}-{{ $terms_array['price_per_kg'] }},<br/>
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                    </td>

                  <td style="font-size:14px;">{{ $Sales_datas['gross_amount'] }}</td>
                  <td style="font-size:14px;">{{ $Sales_datas['paid_amount'] }}</td>
                  <td style="font-size:14px;">{{ $Sales_datas['balance_amount'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
</body>
</html>
