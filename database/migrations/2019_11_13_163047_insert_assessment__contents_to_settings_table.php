<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAssessmentContentsToSettingsTable extends Migration
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
        $setting->value ='<p>&nbsp;</p>
        <p>&nbsp;</p>
        <div style="padding: 3rem; border: 2px solid #000;"><img src="../../../assets/images/logo-icon.png" alt="homepage" width="auto" height="auto" /> <img class="dark-logo" src="../../../assets/images/logo-text.png" alt="homepage" />
        <h1 style="margin-top: 4rem;">EMPLOYEESELF-EVALUATION FORM</h1>
        <table dir="LTR" style="height: 450px;" border="1" width="722" cellspacing="0" cellpadding="7">
        <tbody>
        <tr>
        <td>
        <h2 style="margin-bottom: 0in;">Employee Name:</h2>
        </td>
        <td>
        <p>{employee.name}</p>
        </td>
        </tr>
        <tr>
        <td>
        <h2 style="margin-bottom: 0in;">Employee ID:</h2>
        </td>
        <td>
        <p>&nbsp;{{employee.id}</p>
        </td>
        </tr>
        <tr>
        <td>
        <h2 style="margin-bottom: 0in;">Designation:</h2>
        </td>
        <td>
        <p>&nbsp;{employee.designation_name}</p>
        </td>
        </tr>
        <tr>
        <td>
        <h2 style="margin-bottom: 0in;">Review Period:</h2>
        </td>
        <td>
        <p><a name="_GoBack1"></a>{assessment.from} to {assessment.to}</p>
        </td>
        </tr>
        <tr>
        <td>
        <h2 style="margin-bottom: 0in;">Reporting Head:</h2>
        </td>
        <td>
        <p>&nbsp;{reporthead.name}</p>
        </td>
        </tr>
        </tbody>
        </table>
        <hr style="margin-top: 4rem !important; margin-bottom: 3rem  !important;" />
        <h3><strong><span style="font-family: Cambria, serif; line-height: 30px;">Please complete the questions listed below and submit the same prior to your performance evaluation. As you complete the form, consider your own personal performance as it relates to your current job description and expectations for the review period. </span></strong></h3>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div style="margin-bottom: 15rem;">
        <p><span style="font-family: Cambria, serif;"><span style="font-size: medium;">1. Do you understand the requirements of your job and your job profile? </span></span></p>
        <ul>
        <li>Yes</li>
        <li>No</li>
        </ul>
        <p>If no, what aspects of your job profile needs clarification?</p>
        </div>
        <div style="margin-bottom: 15rem;">
        <p><span style="font-size: medium;">2. What were your expectations for the review period (2018-2019) and have your expectations been fulfilled or lived upto?</span></p>
        </div>
        <div style="margin-bottom: 15rem;">
        <p><span style="font-size: medium;">3. What goal have you achieved during the review period (2018-2019) and what part of your work profile do you MOST/LEAST enjoy? </span></p>
        </div>
        <div style="margin-bottom: 15rem;">
        <p>4. What are your strengths (the things you do well) and how do you put them to use in your position?</p>
        </div>
        <div style="margin-bottom: 15rem;">
        <p>5. What are your weaknesses (the things you don&rsquo;t do so well) and how do they impact your job?</p>
        </div>
        <div style="margin-bottom: 15rem;">
        <p>6. What would help you enhance your performance?</p>
        </div>
        <div style="margin-bottom: 15rem;">
        <p>7. What are your expectations for the coming evaluation period?</p>
        </div>
        <div style="margin-bottom: 15rem;">
        <p>8. How would you rate your overall performance for this review period? (tick any 1)</p>
        <ul>
        <li>Outstanding</li>
        <li>Exceeds Expectations</li>
        <li>Meets Expectations</li>
        <li>Below Expectations</li>
        <li>Unsatisfactory</li>
        </ul>
        </div>
        <div style="margin-bottom: 15rem;">
        <p>9. What are your expectations from the company and in what ways do you think you can contribute to its growth and excellence?</p>
        </div>
        <div style="margin-bottom: 15rem;">
        <p>10. Suggestions/ Feedback/ Queries/ Clarifications, if Any (for the company and your Reporting Head):</p>
        </div>
        <table style="height: 176px; margin-bottom: 3rem;" border="1px solid rgb(0, 0, 10);" width="698" cellspacing="0" cellpadding="7">
        <tbody>
        <tr valign="TOP">
        <td>
        <p><strong>EMPLOYEE NAME:</strong></p>
        </td>
        <td>
        <p>{employee.name}</p>
        </td>
        </tr>
        <tr valign="TOP">
        <td>
        <p><strong>EMPLOYEE SIGN:</strong></p>
        </td>
        <td>
        <p>&nbsp;</p>
        </td>
        </tr>
        <tr valign="TOP">
        <td>
        <p><strong>DATE:</strong></p>
        </td>
        <td>
        <p>&nbsp;{other.current_Date_Month_Year}</p>
        </td>
        </tr>
        </tbody>
        </table>
        <table border="1px solid rgb(0, 0, 10)" width="701" cellspacing="0" cellpadding="7">
        <tbody>
        <tr valign="TOP">
        <td>
        <p style="margin-bottom: 0in;"><span style="font-family: Cambria, serif;"><span style="font-size: medium;"><strong>Reporting Head Comments</strong></span></span></p>
        <p>&nbsp;</p>
        </td>
        <td>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        <p style="margin-bottom: 0in;">&nbsp;</p>
        </td>
        </tr>
        <tr valign="TOP">
        <td>
        <p><strong>Reporting Head Attestation</strong></p>
        </td>
        <td>
        <p><strong>NAME: {reporthead.name}<br /></strong></p>
        <p>&nbsp;</p>
        <p><span style="font-family: Cambria, serif;"><strong>SIGNATURE:</strong></span></p>
        <p>&nbsp;</p>
        <p><strong>DATE: {other.current_Date_Month_Year}<br /></strong></p>
        <p>&nbsp;</p>
        </td>
        </tr>
        </tbody>
        </table>
        <table>
        <tbody>
        <tr>
        <td style="text-align: center;">
        <h4>---------------------for management use---------------------------</h4>
        </td>
        </tr>
        </tbody>
        </table>
        <table border="1px solid rgb(0, 0, 10)" width="701">
        <tbody>
        <tr>
        <td>&nbsp;</td>
        </tr>
        </tbody>
        </table>
        </div>';
        $setting->fieldtype = 'textarea';
        $setting->hint = 'You can change the employeeevoluation content for employees';
        $setting->save();

        $setting = \App\Models\Setting::firstOrNew([
            'name' => 'REPORTHEAD_EVALUATION_FORM',
        ]);
        $setting->value ='<p>&nbsp;</p>
        <div style="padding: 3rem; border: 2px solid #000;">
        <div style="padding-bottom: 2rem;">
        <p style="text-align: center;"><span style="font-size: large;"><strong>EMPLOYEE PERFORMANCE APPRAISAL FORM</strong></span></p>
        </div>
        <p>EMPLOYEE NAME:<strong>{self.name} &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</strong> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span style="font-family: Cambria, serif;"><span style="font-size: medium;">EMPLOYEE ID: <strong>{employee.id}</strong><br /></span></span></p>
        <p class="western" style="text-align: left;">&nbsp;</p>
        <p><span style="font-size: medium;">APPRAISAL PERIOD:</span><span style="font-family: Cambria, serif;"><span style="font-family: Cambria, serif;"><span style="font-size: small;"><strong>&nbsp;{assessment.from} to {assessment.to}<br /></strong></span></span></span></p>
        <table style="margin-top: 4rem;" border="2" width="100%" cellspacing="0" cellpadding="7">
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
        <h3 class="western" style="text-align: center; padding-top: 2rem; padding-bottom: :3rem;" align="center"><span style="font-family: Cambria, serif;">PERFORMANCE APPPRAISAL FORM</span></h3>
        <h4><span style="font-family: Cambria, serif;">RATER&rsquo;S OVERALL COMMENTS:</span>&nbsp;</h4>
        <table style="height: 197px; width: 698px;" border="1" cellspacing="0">
        <tbody style="padding-left: 180px;">
        <tr style="padding-left: 180px;">
        <td style="width: 507px; padding-left: 180px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        <p>&nbsp;</p>
        <h2><span style="font-family: Cambria, serif;">---------------------------------------------------------------------------------------------------------------------------</span></h2>
        <p><strong> OVERALL RATINGS (scale of 1-10):</strong></p>
        <table style="width: 180px; height: 56px;" border="1" cellspacing="0">
        <tbody>
        <tr>
        <td style="width: 170px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        <p><strong>&nbsp; &nbsp; &nbsp;</strong></p>
        <h2>---------------------------------------------------------------------------------------------------------------------------</h2>
        <h3>RATER&rsquo;S NAME: {employee.name}</h3>
        <h3>&nbsp;</h3>
        <h3><span style="font-family: Cambria, serif;">RATER&rsquo;S DESIGNATION: {employee.designation_name}<br /></span></h3>
        <p>&nbsp;</p>
        <p><span style="font-family: Calibri, serif;"><strong>RATER&rsquo;S SIGNATURE:</strong></span></p>
        <p>&nbsp;</p>
        <p><span style="font-family: Calibri, serif;"><span style="font-family: Cambria, serif;"><strong>DATE: {other.current_Date_Month_Year}</strong></span></span></p>
        <h2><span style="font-family: Cambria, serif;">---------------------------------------------------------------------------------------------------------------------------</span></h2>
        <h4 style="text-align: center; padding-bottom: 2rem;"><em>FOR COMPANY MANAGEMENT USE</em></h4>
        <h3 class="western">OVERALL COMMENTS FROM THE MANAGEMENT OF SUMANAS TECHNOLOGIES:</h3>
        <table style="height: 217px;" border="1" width="679" cellspacing="0">
        <tbody>
        <tr>
        <td style="width: 669px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        <h2><span style="font-size: medium;">ATTESTED BY: {employee.name}</span></h2>
        <h2><span style="font-family: Cambria, serif;"><span style="font-size: medium;">DESIGNATION:&nbsp; {employee.designation_name}<br /></span></span></h2>
        <h2><span style="font-family: Cambria, serif;"><span style="font-size: medium;">DATE:&nbsp;{other.current_Date_Month_Year}</span></span> <span style="text-align: right; float: right; font-size: medium;">SIGNATURE </span></h2>
        <h2 class="western" style="text-align: center; padding-left: 120px;">&nbsp;</h2>
        </div>';
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\Setting::whereIn('name', ['SELF_EVALUATION_FORM', 'REPORTHEAD_EVALUATION_FORM'])->forceDelete();   
    }
}
