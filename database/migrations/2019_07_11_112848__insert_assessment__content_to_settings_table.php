<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAssessmentContentToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::firstOrNew([
            'name' => 'SELF_EVALUATION_FORM',
        ]);
        $setting->value = '<p>&nbsp;</p>
        <p>&nbsp;</p>
        <p style="margin-bottom: 0in;" align="CENTER"><span style="font-family: Cambria, serif;"><span style="font-size: large;"><strong>EMPLOYEE SELF-EVALUATION FORM</strong></span></span></p>
        <p style="margin-bottom: 0in;" align="CENTER">&nbsp;</p>
        <table dir="LTR" width="387" cellspacing="0" cellpadding="7" align="CENTER">
        <tbody>
        <tr valign="TOP">
        <td style="border: 1px solid #00000a; padding: 0in 0.08in;" width="155">
        <p style="margin-bottom: 0in;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>Employee Name: </strong></span></span></p>
        <p>&nbsp;</p>
        </td>
        <td style="border: 1px solid #00000a; padding: 0in 0.08in;" width="202">
        <p>&nbsp;{employee.name}</p>
        </td>
        </tr>
        <tr valign="TOP">
        <td style="border: 1px solid #00000a; padding: 0in 0.08in;" width="155">
        <p style="margin-bottom: 0in;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>Employee ID:</strong></span></span></p>
        <p>&nbsp;</p>
        </td>
        <td style="border: 1px solid #00000a; padding: 0in 0.08in;" width="202">
        <p>&nbsp;{employee.employeetype}</p>
        </td>
        </tr>
        <tr valign="TOP">
        <td style="border: 1px solid #00000a; padding: 0in 0.08in;" width="155">
        <p style="margin-bottom: 0in;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>Designation: </strong></span></span></p>
        <p>&nbsp;</p>
        </td>
        <td style="border: 1px solid #00000a; padding: 0in 0.08in;" width="202">
        <p>&nbsp;{employee.designation_name}</p>
        </td>
        </tr>
        <tr valign="TOP">
        <td style="border: 1px solid #00000a; padding: 0in 0.08in;" width="155">
        <p style="margin-bottom: 0in;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>Review Period: </strong></span></span></p>
        <p>&nbsp;</p>
        </td>
        <td style="border: 1px solid #00000a; padding: 0in 0.08in;" width="202">
        <p><a name="_GoBack1"></a>{assessment.from} to {assessment.to}</p>
        </td>
        </tr>
        <tr valign="TOP">
        <td style="border: 1px solid #00000a; padding: 0in 0.08in;" width="155">
        <p style="margin-bottom: 0in;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>Reporting Head</strong></span></span></p>
        <p>&nbsp;</p>
        </td>
        <td style="border: 1px solid #00000a; padding: 0in 0.08in;" width="202">
        <p>&nbsp;{reporthead.name}</p>
        </td>
        </tr>
        </tbody>
        </table>
        <hr style="border-style: dotted;" />
        <p style="margin-bottom: 0.11in;"><span style="font-family: Cambria, serif;"><strong>&nbsp; &nbsp; &nbsp;Please complete the questions listed below and submit the same prior to your performance evaluation. As you complete the form, consider your own&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; personal performance as it relates to your current job description and expectations for the review period. </strong></span></p>
        <p style="margin-bottom: 0.11in;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">1. Do you understand the requirements of your job and your job profile? </span></span></p>
        <p style="padding-left: 60px;">&nbsp;</p>
        <p style="margin-bottom: 0.11in; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">Yes </span></span></p>
        <p style="padding-left: 60px;">&nbsp;</p>
        <p style="margin-bottom: 0.11in; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">No </span></span></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">If no, what aspects of your job profile needs clarification? </span></span></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">2. What were your expectations for the review period (2018-2019) and have your expectations been fulfilled or lived upto?</span></span></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">3. What goal have you achieved during the review period (2018-2019) and what part of your work profile do you MOST/LEAST enjoy? </span></span></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">4. What are your strengths (the things you do well) and how do you put them to use in your position? </span></span></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">5. What are your weaknesses (the things you don&rsquo;t do so well) and how do they impact your job? </span></span></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">6. What would help you enhance your performance? </span></span></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">7. What are your expectations for the coming evaluation period? </span></span></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-left: 0.25in; margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">8. How would you rate your overall performance for this review period? (tick any 1)</span></span></p>
        <p style="padding-left: 60px;">&nbsp;</p>
        <p style="margin-bottom: 0.11in; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">Outstanding </span></span></p>
        <p style="padding-left: 60px;">&nbsp;</p>
        <p style="margin-bottom: 0.11in; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">Exceeds Expectations </span></span></p>
        <p style="padding-left: 60px;">&nbsp;</p>
        <p style="margin-bottom: 0.11in; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">Meets Expectations</span></span></p>
        <p style="padding-left: 60px;">&nbsp;</p>
        <p style="margin-bottom: 0.11in; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">Below Expectations </span></span></p>
        <p style="padding-left: 60px;">&nbsp;</p>
        <p style="margin-bottom: 0.11in; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">Unsatisfactory </span></span></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">9. What are your expectations from the company and in what ways do you think you can contribute to its growth and excellence?</span></span></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><br /><br /></p>
        <p style="margin-bottom: 0.11in; text-align: left; padding-left: 60px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">10. Suggestions/ Feedback/ Queries/ Clarifications, if Any (for the company and your Reporting Head):</span></span></p>
        <p style="margin-bottom: 0.11in;"><br /><br /></p>
        <p style="margin-bottom: 0.11in;"><br /><br /></p>
        <p style="margin-bottom: 0.11in;"><br /><br /></p>
        <p style="margin-bottom: 0.11in;"><br /><br /></p>
        <p style="margin-bottom: 0.11in;"><br /><br /></p>
        <p style="margin-bottom: 0.11in;"><br /><br /></p>
        <table width="595" cellspacing="0" cellpadding="7" align="CENTER">
        <tbody>
        <tr valign="TOP">
        <td style="border: 1px solid #00000a; padding: 0in 0.08in 0in 0.08in;" width="202" height="27">
        <p><span style="font-family: Cambria, serif;"><strong>EMPLOYEE NAME:</strong></span></p>
        </td>
        <td style="border: 1px solid #00000a; padding: 0in 0.08in 0in 0.08in;" width="363">
        <p>&nbsp;{employee.name}</p>
        </td>
        </tr>
        <tr valign="TOP">
        <td style="border: 1px solid #00000a; padding: 0in 0.08in 0in 0.08in;" width="202" height="29">
        <p><span style="font-family: Cambria, serif;"><strong>EMPLOYEE SIGN:</strong></span></p>
        </td>
        <td style="border: 1px solid #00000a; padding: 0in 0.08in 0in 0.08in;" width="363">
        <p>&nbsp;</p>
        </td>
        </tr>
        <tr valign="TOP">
        <td style="border: 1px solid #00000a; padding: 0in 0.08in 0in 0.08in;" width="202" height="27">
        <p><span style="font-family: Cambria, serif;"><strong>DATE:</strong></span></p>
        </td>
        <td style="border: 1px solid #00000a; padding: 0in 0.08in 0in 0.08in;" width="363">
        <p>&nbsp;{other.current_Date_Month_Year}</p>
        </td>
        </tr>
        </tbody>
        </table>
        <p style="margin-bottom: 0.11in;"><br /><br /></p>
        <p style="margin-bottom: 0.11in;"><br /><br /></p>
        <table width="601" cellspacing="0" cellpadding="7" align="CENTER">
        <tbody>
        <tr valign="TOP">
        <td style="border: 1px solid #00000a; padding: 0in 0.08in 0in 0.08in;" width="202">
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>Reporting Head Comments</strong></span></span></p>
        <p>&nbsp;</p>
        </td>
        <td style="border: 1px solid #00000a; padding: 0in 0.08in 0in 0.08in;" width="369">
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p>&nbsp;</p>
        </td>
        </tr>
        <tr valign="TOP">
        <td style="border: 1px solid #00000a; padding: 0in 0.08in 0in 0.08in;" width="202">
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>Reporting Head Attestation</strong></span></span></p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p>&nbsp;</p>
        </td>
        <td style="border: 1px solid #00000a; padding: 0in 0.08in 0in 0.08in;" width="369">
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;"><span style="font-family: Cambria, serif;"><strong>NAME: {reporthead.name}<br /></strong></span></p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;"><span style="font-family: Cambria, serif;"><strong>SIGNATURE:</strong></span></p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;"><span style="font-family: Cambria, serif;"><strong>DATE: {other.current_Date_Month_Year}<br /></strong></span></p>
        <p>&nbsp;</p>
        </td>
        </tr>
        </tbody>
        </table>
        <p style="margin-bottom: 0.11in;"><br /><br /></p>
        <table width="601" cellspacing="0" cellpadding="7" align="CENTER">
        <tbody>
        <tr>
        <td style="border: 1px solid #00000a; padding: 0in 0.08in 0in 0.08in;" valign="TOP" width="585">
        <p style="margin-bottom: 0in;" align="CENTER">&nbsp;</p>
        <span style="font-family: Cambria, serif;"><em><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;---------------------for management use---------------------------</strong></em></span>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p>&nbsp;</p>
        </td>
        </tr>
        </tbody>
        </table>
        <p style="margin-bottom: 0.11in;"><br /><br /></p>';
        $setting->fieldtype = 'textarea';
        $setting->hint = 'You can change the employeeevoluation content for employees';
        $setting->save();

        $setting = \App\Models\Setting::firstOrNew([
            'name' => 'REPORTHEAD_EVALUATION_FORM',
        ]);
        $setting->value = '<p class="western" align="center"><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><span style="font-size: large;"><strong>EMPLOYEE PERFORMANCE APPRAISAL FORM</strong></span></span></span></span></p>
        <p class="western" style="text-align: center;">&nbsp;</p>
        <p class="western" style="text-align: left; padding-left: 120px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">EMPLOYEE NAME: </span></span><strong>{self.name} &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</strong> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span style="font-family: Cambria, serif;"><span style="font-size: medium;">EMPLOYEE ID: <strong>{employee.employeetype}</strong><br /></span></span></p>
        <p class="western" style="text-align: left;">&nbsp;</p>
        <p class="western" style="text-align: left; padding-left: 120px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">APPRAISAL PERIOD:</span></span> <span style="font-family: Cambria, serif;"><span style="font-family: Cambria, serif;"><span style="font-size: small;"><strong>&nbsp;{assessment.from} to {assessment.to}<br /></strong></span></span></span></p>
        <p class="western" style="text-align: center;">&nbsp;</p>
        <p class="western" style="text-align: center;">&nbsp;</p>
        <table style="margin-left: auto; margin-right: auto;" border="2" width="691" cellspacing="0" cellpadding="7">
        <tbody>
        <tr valign="top">
        <td rowspan="2" width="384" height="21">
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"> INSTRUCTIONS: This appraisal form must be completed by the immediate manager based on performance standards previously established. If the selected category is &ldquo;Achieves Standards&rdquo; the manager must indicate the level of rating:</span></span></span></p>
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"> <span style="font-family: Cambria, serif;"><strong>M=Marginal or P=Proficient. </strong></span> </span></span></p>
        </td>
        <td rowspan="2" width="82">
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><strong>EXCEEDS</strong></span> <span style="font-family: Cambria, serif;"><strong>STANDARDS</strong></span></span></span></p>
        </td>
        <td colspan="3" width="82">
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><strong>ACHIEVES</strong></span> <span style="font-family: Cambria, serif;"><strong>STANDARDS</strong></span></span></span></p>
        </td>
        <td rowspan="2" width="77">
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><strong>BELOW</strong></span> <span style="font-family: Cambria, serif;"><strong>STANDARDS</strong></span></span></span></p>
        </td>
        </tr>
        <tr valign="top">
        <td width="34">
        <p align="center"><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>P</strong></span></span></span></span></p>
        </td>
        <td width="34">
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>M</strong></span></span></span></span></p>
        </td>
        </tr>
        </tbody>
        <tbody>
        <tr valign="top">
        <td width="384" height="56">
        <p>&nbsp;</p>
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><strong>UNDERSTANDING OF JOB ROLE</strong></span><span style="font-family: Cambria, serif;"><strong>:</strong></span></span></span></p>
        </td>
        <td width="82">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td colspan="2" width="77">
        <p class="western">&nbsp;</p>
        </td>
        </tr>
        </tbody>
        <tbody>
        <tr valign="top">
        <td width="384" height="54">
        <p>&nbsp;</p>
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><strong>QUALITY OF</strong></span> <span style="font-family: Cambria, serif;"><strong>WORK:</strong></span></span></span></p>
        </td>
        <td width="82">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td colspan="2" width="77">
        <p class="western">&nbsp;</p>
        </td>
        </tr>
        </tbody>
        <tbody>
        <tr valign="top">
        <td width="384" height="54">
        <p>&nbsp;</p>
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><strong>PRODUCTIVITY:</strong></span></span></span></p>
        </td>
        <td width="82">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td colspan="2" width="77">
        <p class="western">&nbsp;</p>
        </td>
        </tr>
        </tbody>
        <tbody>
        <tr valign="top">
        <td width="384" height="54">
        <p>&nbsp;</p>
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><strong>DEPENDABILITY:</strong></span></span></span></p>
        </td>
        <td width="82">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td colspan="2" width="77">
        <p class="western">&nbsp;</p>
        </td>
        </tr>
        </tbody>
        <tbody>
        <tr valign="top">
        <td width="384" height="44">
        <p>&nbsp;</p>
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><strong>INTERPERSONAL RELATIONS:</strong></span></span></span></p>
        </td>
        <td width="82">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td colspan="2" width="77">
        <p class="western">&nbsp;</p>
        </td>
        </tr>
        </tbody>
        <tbody>
        <tr valign="top">
        <td width="384" height="35">
        <p>&nbsp;</p>
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><strong>RESPONSIVENESS</strong></span><span style="font-family: Cambria, serif;"><strong>:</strong></span></span></span></p>
        </td>
        <td width="82">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td colspan="2" width="77">
        <p class="western">&nbsp;</p>
        </td>
        </tr>
        </tbody>
        <tbody>
        <tr valign="top">
        <td width="384" height="29">
        <p>&nbsp;</p>
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><strong>PLANNING AND ORGANIZATION OF WORK:</strong></span></span></span></p>
        </td>
        <td width="82">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td colspan="2" width="77">
        <p class="western">&nbsp;</p>
        </td>
        </tr>
        </tbody>
        <tbody>
        <tr valign="top">
        <td width="384" height="44">
        <p>&nbsp;</p>
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><strong>INITIATIVE:</strong></span> </span></span></p>
        </td>
        <td width="82">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td colspan="2" width="77">
        <p class="western">&nbsp;</p>
        </td>
        </tr>
        </tbody>
        <tbody>
        <tr valign="top">
        <td width="384" height="41">
        <p>&nbsp;</p>
        <p><span style="font-family: Calibri, serif;"><span style="font-size: small;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>OVERALL</strong></span></span> <span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>APPRAISAL RATING:</strong></span></span> <span style="font-family: Cambria, serif;"><span style="font-size: medium;">(ONE</span></span> <span style="font-family: Cambria, serif;"><span style="font-size: medium;">CATEGORY MUST</span></span><span style="font-family: Cambria, serif;"><span style="font-size: medium;"> BE</span></span> <span style="font-family: Cambria, serif;"><span style="font-size: medium;">CHECKED)</span></span></span></span></p>
        </td>
        <td width="82">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td width="34">
        <p class="western">&nbsp;</p>
        </td>
        <td colspan="2" width="77">
        <p class="western">&nbsp;</p>
        </td>
        </tr>
        </tbody>
        </table>
        <h3 class="western" style="text-align: center;" align="center"><span style="font-family: Cambria, serif;">PERFORMANCE APPPRAISAL FORM</span></h3>
        <h4 class="western" style="text-align: left; padding-left: 120px;" align="center"><span style="font-family: Cambria, serif;">RATER&rsquo;S OVERALL COMMENTS:</span>&nbsp;</h4>
        <table style="height: 197px; width: 698px; margin-left: auto; margin-right: auto;" border="2">
        <tbody style="padding-left: 180px;">
        <tr style="padding-left: 180px;">
        <td style="width: 507px; padding-left: 180px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        <p>&nbsp;</p>
        <p style="padding-left: 120px;"><strong>OVERALL RATINGS (scale of 1-10):&nbsp;</strong></p>
        <table style="height: 51px; width: 123px; margin-right: auto; margin-left: 390px;" border="2" align="center">
        <tbody style="padding-left: 30px;">
        <tr style="padding-left: 30px;">
        <td style="width: 4px; padding-left: 30px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        <p class="western">&nbsp;</p>
        <h3 class="western" style="padding-left: 120px;"><span style="font-family: Cambria, serif;">RATER&rsquo;S NAME: {employee.name}<br /></span></h3>
        <h3 class="western" style="padding-left: 120px;">&nbsp;</h3>
        <h3 class="western" style="padding-left: 120px;"><span style="font-family: Cambria, serif;">RATER&rsquo;S DESIGNATION: {employee.designation_name}<br /></span></h3>
        <p class="western" style="padding-left: 120px;">&nbsp;</p>
        <p class="western" style="padding-left: 120px;"><span style="font-family: Calibri, serif;"><strong>RATER&rsquo;S SIGNATURE:</strong></span></p>
        <p class="western" style="padding-left: 120px;">&nbsp;</p>
        <p class="western" style="padding-left: 120px;"><span style="font-family: Calibri, serif;"><span style="font-family: Cambria, serif;"><strong>DATE: {other.current_Date_Month_Year}</strong></span></span></p>
        <h2 class="western" style="text-align: center; padding-left: 60px;"><span style="font-family: Cambria, serif;">----------------------------------------------------------------------------------------------------------------------------</span></h2>
        <h4 class="western" style="padding-left: 180px; text-align: right;"><span style="font-family: Cambria, serif;"><em>FOR COMPANY MANAGEMENT USE</em></span></h4>
        <h4 class="western" style="padding-left: 240px;">&nbsp;</h4>
        <h4 class="western">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;OVERALL COMMENTS FROM THE MANAGEMENT OF TechnoKryon:</h4>
        <h2 class="western">&nbsp;</h2>
        <table style="width: 698px; margin-left: 120px; height: 217px;" border="1">
        <tbody style="padding-left: 120px;">
        <tr style="height: 80.625px; padding-left: 120px;">
        <td style="width: 449px; height: 80.625px; padding-left: 120px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        <p>&nbsp;</p>
        <h2 class="western" style="padding-left: 120px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">ATTESTED BY: {employee.name}<br /></span></span></h2>
        <h2 class="western" style="padding-left: 180px;">&nbsp;</h2>
        <h2 class="western" style="padding-left: 120px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">DESIGNATION:&nbsp; {employee.designation_name}<br /></span></span></h2>
        <h2 class="western" style="padding-left: 120px;">&nbsp;</h2>
        <h2 class="western" style="padding-left: 120px;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;">DATE:&nbsp;{other.current_Date_Month_Year}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; SIGNATURE</span></span></h2>
        <h2 class="western" style="text-align: center; padding-left: 120px;">&nbsp;</h2>';
        $setting->fieldtype = 'textarea';
        $setting->hint = 'You can change the reporthead evaluation content for employees';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \App\Models\Setting::whereIn('name', ['SELF_EVALUATION_FORM', 'REPORTHEAD_EVALUATION_FORM'])->forceDelete();
    }
}
