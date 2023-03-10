 {{ Form::open(['route' => "setting.getEmployeePayslipForm",'method' => 'POST', 'class' => 'form-horizontal', 'id' =>'payslip_form_2']) }}
 {{ Form::hidden('toemployee',@$toemployee) }}

    <table width="621" cellspacing="0" cellpadding="7">
        <tbody>
            <tr valign="top">
                <td style="background: #d9d9d9;" bgcolor="#d9d9d9" width="191">
                    <p align="center"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>EARNINGS</strong></span></span></p>
                </td>
                <td style="background: #d9d9d9;" bgcolor="#d9d9d9" width="81">
                    <p align="center"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>AMOUNT (in Rs.)</strong></span></span></p>
                </td>
                <td style="background: #d9d9d9;" bgcolor="#d9d9d9" width="166">
                    <p align="center"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>DEDUCTIONS</strong></span></span></p>
                </td>
                <td style="background: #d9d9d9;" bgcolor="#d9d9d9" width="126">
                    <p align="center"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>AMOUNT (in Rs.)</strong></span></span></p>
                </td>
            </tr>
            <tr valign="top">
                <td width="191"><br>
                    <p><span style="font-family: Cambria, serif;">Basic Salary</span></p><br>
                    <p><span style="font-family: Cambria, serif;">Dearness Allowance (DA)</span></p><br>
                    <p><span style="font-family: Cambria, serif;">House Rent Allowance (HRA)</span></p><br>
                    <p><span style="font-family: Cambria, serif;">Special Allowance</span></p>
                </td>
                <td width="81">
                    <p align="right"> <div class="form-group">
                        {{ Form::text('payslip[basic_pay]',@$BP, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::text('payslip[dearness_allowance]', @$DA, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::text('payslip[house_rent_allowance]',  @$HRA, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::text('payslip[special_allowance]',  @$special_allowance, ['class' => 'form-control']) }}
                    </div></p>
                </td>
                <td width="166">
                    <p><span style="font-family: Cambria, serif;">Employee Provident Fund</span></p><br>
                    <p><span style="font-family: Cambria, serif;">E.S.I </span></p><br>
                    <p><span style="font-family: Cambria, serif;">LOP</span></p><br>
                    <p><span style="font-family: Cambria, serif;">TDS</span></p><br>
                </td>
                <td width="126">
                    <p align="right"> <div class="form-group">
                        {{ Form::text('payslip[epf]',  @$EPF, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::text('payslip[esi]', @$ESI, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::text('payslip[leaves]', @round($leaves), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                            {{ Form::text('payslip[tds]',@$tds, ['class' => 'form-control']) }}
                        </div></p>
                </td>
            </tr>
            <tr valign="top">
                <td width="191" height="24">
                    <p><span style="font-family: Cambria, serif;"><strong>GROSS PAY</strong></span></p>
                </td>
                <td width="81">
                    {{ Form::text('payslip[gross_pay]',  @$gross_pay, ['class' => 'form-control']) }}
                    <p align="right">&nbsp;</p>
                </td>
                <td width="166">
                    <p><span style="font-family: Cambria, serif;"><strong>TOTAL DEDUCTIONS</strong></span></p>
                </td>
                <td width="126">
                    {{ Form::text('payslip[total_deduction]',  @round($total_deduction), ['class' => 'form-control']) }}
                    <p align="right"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong></strong></span></span></p>
                </td>
            </tr>
            <tr valign="top">
                <td colspan="2" width="285" height="37">
                    <p><span style="font-family: Cambria, serif;"><span style="font-size: large;"><strong>NET PAY </strong></span></span></p>
                </td>
                <td colspan="2" width="306">
                    {{ Form::text('payslip[net_pay]', @round($net_pay), ['class' => 'form-control']) }}
                </td>
            </tr>
        </tbody>
    </table>
    <tr valign="top">
        <td colspan="2" >
            <div class="col text-center">
                <br><button type="submit" class="btn btn-success" id="btn-generate" ><i class="fa fa-wrench"></i> Generate</button>
            </div>
        </td>
    </tr>
{{ Form::close() }}