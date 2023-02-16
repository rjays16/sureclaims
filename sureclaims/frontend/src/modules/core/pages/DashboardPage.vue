<template>
  <layout>
    <template>
      <el-carousel :interval="4000" type="card" height="200px">
        <el-carousel-item v-for="img in images" :key="img">
          <img :src="img" width="100%" style="margin-bottom: 200px"/>
        </el-carousel-item>
      </el-carousel>
    </template>
    <div>
      <el-container>
        <el-card class="box-card col-12">
          <div slot="header" class="clearfix">
            <i class="la la-cog"></i>
            <span>News</span>
            <!--<el-button style="float: right; padding: 3px 0" type="text">Operation button</el-button>-->
      </div>
          <div class="text item">
            <el-collapse v-model="activeNames" @change="handleChange">
              <el-collapse-item title="PhilHealth certifies Segworks as Health Information Technology Providers (HITP)" name="1">
                <p >
                  After months of rigorous conformance and testing process, PhilHealth issued to Segworks Technologies Corporation
                  its Certificate of Accreditation as e-Claims provider. In a bold and innovative move, PhilHealth decided to
                  outsource the automation of health insurance claims reimbursement to <b>health information technology providers</b>
                  (HITP) that meet its stringent process and security standards. Through the services of a certified HITP, the
                  whole process of membership status verification, claims submission, and claims payment status monitoring, is
                  now accomplished by hospitals electronically. On April 4, 2013, Segworks received a copy of certificate from
                  the HITP Accreditation Committee of PhilHealth led by its Chairman, <b>Dr. Alvin Marcelo</b>.
                </p>

                <p>
                  <b>Philippine Health Insurance Corporation</b> (PhilHealth) is committed to ensuring ease of availment at the point of
                  care for all its beneficiaries.
                </p>
                <p>
                  As such, in 2011, the Corporation launched the eClaims Project through PhilHealth Circular No.14 and Office Order
                  No. 69 which broadly aims to streamline key processes such as eligibility check, claims submission, verification
                  and payment in order to serve both members and partner providers better. The eClaims Project had three phases,  namely
                </p>
                <ul>
                  <li>Phase I or Claims Eligibility Web Service (CEWS)</li>
                  <li>Phase II or Electronic Claims Submission (ECS)</li>
                  <li>Phase III or Claims Status Verification/Payment (CSV)</li>
                </ul>
                <a target="_blank" rel="noopener noreferrer" href="https://www.philhealth.gov.ph/news/2018/archives.php">Read more news...</a>
              </el-collapse-item>
              <!--<el-collapse-item title="PhilHealth: New Policy on Benefit Eligibility to Start in October " name="2">-->
                <!--<p>-->
                  <!--The Philippine Health Insurance Corporation (PhilHealth) reminds the public anew that the sufficient regularity of-->
                  <!--payment (SRP) will be required for all benefit availments starting October 1, 2018.-->
                <!--</p>-->
                <!--<p>-->
                  <!--Previously set for implementation last January, PhilHealth postponed the execution of the directive to provide its-->
                  <!--members with ample time to update their premium contributions in order to ensure themselves and their dependents of-->
                  <!--uninterrupted social health insurance coverage.-->
                <!--</p>-->
                <!--<p>-->
                  <!--The SRP rule is contained in PhilHealth Circular No. 2017-0021 which states that, “...to establish sufficient-->
                  <!--regularity of payment, members should have paid six (6) months contributions preceding the three (3) months-->
                  <!--qualifying contributions within the twelve (12)-month period prior to the first day of confinement...”-->
                <!--</p>-->
                <!--<p>-->
                  <!--In effect, to be eligible to PhilHealth benefits, a member should have paid at least nine (9) months’ worth of-->
                  <!--premiums in the twelve (12) months preceding the confinements including the confinement month of the patient.-->
                <!--</p>-->
                <!--<p>-->
                  <!--“We call on our members to see to it that their membership information and premium contributions are up to date-->
                  <!--in order to maintain an active PhilHealth membership. In doing so, they ensure themselves and their qualified-->
                  <!--dependents of social health insurance benefits for their medical needs,” said PhilHealth Acting President and-->
                  <!--CEO Dr. Roy B. Ferrer.-->
                <!--</p>-->
                <!--<p>-->
                  <!--Further inquiries on this policy may be referred to PhilHealth’s 24/7 Corporate Action Center Hotline, (02)-->
                  <!--441-7442. E-mails may also be sent to actioncenter@philhealth.gov.ph for clarifications, comments, and suggestions.-->
                  <!--For more updates, members and stakeholders are encouraged to follow PhilHealth on Twitter (@teamphilhealth)-->
                  <!--and Facebook (facebook.com/PhilHealth). END (JJ Rico)-->
                <!--</p>-->
                <!--<a target="_blank" rel="noopener noreferrer" href="https://www.philhealth.gov.ph/news/2018/archives.php">Read more...</a>-->
              <!--</el-collapse-item>-->
              <!--<el-collapse-item title="Use EPRS, PhilHealth Chief urged Employers" name="3">-->
                <!--<p>-->
                  <!--Acting President and Chief Executive Officer of the Philippine Health Insurance Corporation (PhilHealth) Dr. Roy B.-->
                  <!--Ferrer urged members of the Employers Confederation of the Philippines (ECOP) to use the Electronic Premium-->
                  <!--Remittance System or EPRS during its 36th Members’ General Meeting held in Global City in Taguig, Metro Manila.-->
                <!--</p>-->
                <!--<p>-->
                  <!--Ferrer said that using the online facility system will make employer’s premium payment and reporting fast and-->
                  <!--convenient. "PhilHealth is moving forward, modernizing its processes, and changing directions from manual to-->
                  <!--computer based system," said Ferrer.-->
                <!--</p>-->
                <!--<p>-->
                  <!--PhilHealth introduced the EPRS to employers way back in 2012 to veer away from the manual preparation and-->
                  <!--submission of PhilHealth remittance reports. It is a web-based application designed to allow access to employee-->
                  <!--information through the employer’s PhilHealth Employer Engagement Representatives (PEER).-->
                <!--</p>-->
                <!--<p>-->
                  <!--The PhilHealth chief also advised employees to visit the PhilHealth website to verify their payment status and-->
                  <!--immediately notify the Corporation regarding employers who are not regularly remitting and filing their-->
                  <!--remittance reports. "We are strengthening our efforts to sustain targets for premium collection in order-->
                  <!--to come up with more innovations on benefits for all PhilHealth members and dependents," he added.-->
                <!--</p>-->
                <!--<p>-->
                  <!--Meanwhile, formal economy, senior citizens and lifetime members of PhilHealth and their qualified dependents-->
                  <!--will soon enjoy the Expanded Primary Care Benefit (ePCB) Package. The benefit package includes health screening-->
                  <!--and assessment/consultation, essential services such as complete blood count, fecalysis, and urinalysis as-->
                  <!--well as medicines like antibiotics among others.-->
                <!--</p>-->
                <!--<p>-->
                  <!--Likewise, the state health insurer also called on hospitals nationwide with the capacity to provide the ePCB-->
                  <!--essential services to apply for accreditation with PhilHealth. “We are calling on PCB providers to be accredited-->
                  <!--with us so that we can make this package available and accessible to our members particularly to our beloved-->
                  <!--workers in the government and private sectors sooner than expected,” the PhilHealth chief said.-->
                <!--</p>-->
                <!--<p>-->
                  <!--Also in attendance during the ECOP members’ general meeting are the Social Security System, Pag-IBIG Fund, and-->
                  <!--Employee’s Compensation Commission. END (Evelyn P. de Leon)-->
                <!--</p>-->
                <!--<a target="_blank" rel="noopener noreferrer" href="https://www.philhealth.gov.ph/news/2018/use_eprs.html">Read more...</a>-->
              <!--</el-collapse-item>-->
            </el-collapse>
          </div>
        </el-card>
      </el-container>

      <!--<chart-graph />-->
    </div>
  </layout>
</template>

<script>
  import {
    Container,
    Header,
    Main,
    Aside,
    Footer,
    Card,
    Button,
    Collapse,
    CollapseItem,
    Carousel,
    CarouselItem
  } from 'element-ui';
  import SetsPageMeta from '@/modules/core/mixins/SetsPageMeta';
  import UsesLayout from '@/modules/core/mixins/UsesLayout';
  // import ChartGraph from '../components/chart/ChartGraph';

  export default {
    mixins: [SetsPageMeta, UsesLayout],

    components: {
      'el-container': Container,
      'el-header': Header,
      'el-main': Main,
      'el-footer': Footer,
      'el-aside': Aside,
      'el-card': Card,
      'el-button': Button,
      'el-collapse': Collapse,
      'el-collapse-item': CollapseItem,
      'el-carousel': Carousel,
      'el-carousel-item': CarouselItem,
      // 'chart-graph': ChartGraph,
    },

    data() {
      return {
        activeNames: ['1'],
        images: [
          'https://www.philhealth.gov.ph/news/2018/images/use_eprs1.jpg',
          'https://www.philhealth.gov.ph/news/2018/images/holds_consult1.jpg',
          'https://www.philhealth.gov.ph/news/2018/images/holds_consult2.jpg',
        ],
      };
    },
    created() {
      window.location.search = '';
    },
  };
</script>

<style>
  .text {
    font-size: 14px;
  }

  .item {
    margin-bottom: 18px;
  }

  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
  .clearfix:after {
    clear: both
  }

  .box-card {
    width: 480px;
  }

  .el-carousel__item h3 {
    color: #475669;
    font-size: 14px;
    opacity: 0.75;
    line-height: 200px;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    /*background-color: #99a9bf;*/
  }

  .el-carousel__item:nth-child(2n+1) {
    /*background-color: #d3dce6;*/
  }
</style>
