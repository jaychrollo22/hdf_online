<template>
<div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex flex-column">
                        <h2 class="text-white font-weight-bold my-2 mr-5">HDF Online</h2>
                        <div class="d-flex align-items-center font-weight-bold my-2">
                            <a href="#" class="opacity-75 hover-opacity-100">
                                <i class="flaticon2-shelter text-white icon-1x"></i>
                            </a>
                            <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Home</a>
                            <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Generate QR</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <a href="#" class="btn btn-transparent-white font-weight-bold py-3 px-6 mr-2" @click="clearForm">Generate New QR</a>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 mt-2" v-if="qr_hdf && newQR">
                        <div class="card card-custom">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">Generated QR</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <img :src="'hdf_qr/'+qr_hdf.id+'.png'" width="100%" height="auto" @error="imageLoadError">
                                <strong class="text-danger">Expiry Date: {{qr_hdf.expiry_date}}</strong>
                            </div>
                        </div>
                    </div>
                    <div :class="newQR ? 'col-xl-8 mt-2' : 'col-xl-12 mt-2' ">
                        <div class="card card-custom">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">Health Declaration Form</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                 <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <p v-if="language=='English'">1. Did you visit a hospital, clinic or medical health facility in the past 10 days?</p>
                                        <p v-if="language=='Tagalog'">1. Bumisita ka ba sa isang ospital, clinic o health facility noong nakaraang 10 na araw?</p>
                                        <p v-if="language=='Hiligaynon'">1. Naka bisita ka bala sa hospital, klinika, o kun sa mga pasilidad medical sa nagligad nga 10 ka adlaw?</p>
                                        <p v-if="language=='Bisaya'">1. Nakabisita ba ka ug hospital, clinic o balay tambalanan sa nilabay nga 10 ka adlaw?</p>
                                        <div class="custom-control custom-radio custom-control-inline ml-4">
                                            <input type="radio" id="yes1" name="one_question" class="custom-control-input" value="Yes" v-model="form.one_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="yes1" >Yes</label>
                                            <label v-if="language=='Tagalog' || language=='Bisaya'" class="custom-control-label" for="yes1" >Oo</label>
                                            <label v-if="language=='Hiligaynon'" class="custom-control-label" for="yes1" >Huo</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="no1" name="one_question" class="custom-control-input" value="No" v-model="form.one_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="no1">No</label>
                                            <label v-if="language == 'Tagalog' || language == 'Hiligaynon'" class="custom-control-label" for="no1">Hindi</label>
                                            <label v-if="language == 'Bisaya'" class="custom-control-label" for="no1">Dili</label>
                                        </div>
                                        <br>
                                        <span class="text-danger" v-if="errors.one_question">{{ errors.one_question[0] }}</span>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p v-if="language == 'English'">2. Are you aware of COVID-19 Health and Safety Protocol such as wearing of facemask and face shield and social distancing?</p>
                                        <p v-if="language == 'Tagalog'">2. Alam mo ba ang COVID-19 Health and Safety Protocol tulad ng pagsusuot ng facemask at face shield at pagdistansya sa ibang tao?</p>
                                        <p v-if="language == 'Hiligaynon'">2. Nakahibalu ka bala sg mga CoViD-19 Health and Safety Protocols kapareho sg pagsuksuk sg facemask kag face shields kag pag mentinar sg social distancing?</p>
                                        <p v-if="language == 'Bisaya'">2. Nakahibalo ba ka sa COVID 19 Health and Safety Protocol sama sa pag suot ug face mask, face shield ug pag distansya sa isig ka tawo?</p>
                                        <div class="custom-control custom-radio custom-control-inline ml-4">
                                            <input type="radio" id="yes2" name="two_question" class="custom-control-input" value="Yes" v-model="form.two_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="yes2" >Yes</label>
                                            <label v-if="language=='Tagalog' || language=='Bisaya'" class="custom-control-label" for="yes2" >Oo</label>
                                            <label v-if="language=='Hiligaynon'" class="custom-control-label" for="yes2" >Huo</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="no2" name="two_question" class="custom-control-input" value="No" v-model="form.two_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="no2">No</label>
                                            <label v-if="language == 'Tagalog' || language == 'Hiligaynon'" class="custom-control-label" for="no2">Hindi</label>
                                            <label v-if="language == 'Bisaya'" class="custom-control-label" for="no2">Dili</label>
                                        </div>
                                        <br>
                                        <span class="text-danger" v-if="errors.two_question">{{ errors.two_question[0] }}</span>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p v-if="language == 'English'">3. Within the past 10 days, did a doctor or nurse, tell you to self-monitor, self-isolate, or self-quarantine because of concerns about COVID-19 infection?</p>
                                        <p v-if="language == 'Tagalog'">3. Sa nagdaang 10 na araw, sinabi ba sa iyo ng isang doktor o nars na subaybayan ang sarili, ihiwalay sa sarili, o mai-quarantine dahil sa posibleng impeksyon ng COVID-19?</p>
                                        <p v-if="language == 'Hiligaynon'">3. Sa nagligad nga 10 ka adlaw, nakapanugyan bala sa imo ang doctor o nars nga bantayan ang imo kaugalingun, mag separar sa isa ka lugar, o kun mag kuwarantin bangud sa kabalaka sa impeksyon sa CoViD-19?</p>
                                        <p v-if="language == 'Bisaya'">3. Sa nilabay nga 10 ka adlaw,  aduna bay doctor or nurse, nag-ingon kanimo nga bantayan ang kaugalingon,  mag-inusara sa usa ka lugar or mag quarantine tungod sa kabalaka sa Covid 19 impeksyon? </p>
                                        <div class="custom-control custom-radio custom-control-inline ml-4">
                                            <input type="radio" id="yes3" name="three_question" class="custom-control-input" value="Yes" v-model="form.three_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="yes3" >Yes</label>
                                            <label v-if="language == 'Tagalog' || language=='Bisaya'" class="custom-control-label" for="yes3" >Oo</label>
                                            <label v-if="language == 'Hiligaynon'" class="custom-control-label" for="yes3" >Huo</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="no3" name="three_question" class="custom-control-input" value="No" v-model="form.three_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="no3">No</label>
                                            <label v-if="language == 'Tagalog' || language == 'Hiligaynon'" class="custom-control-label" for="no3">Hindi</label>
                                            <label v-if="language == 'Bisaya'" class="custom-control-label" for="no3">Dili</label>
                                        </div>
                                        <br>
                                        <span class="text-danger" v-if="errors.three_question">{{ errors.three_question[0] }}</span>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p v-if="language == 'English'">4. Have you been exposed to a person with a suspected or confirmed case of COVID 19 in the past 10 days?</p>
                                        <p v-if="language == 'Tagalog'">4. Ikaw ba ay may nakasamang tao na pinaghihinalaan o nakumpirma na may COVID 19 sa nagdaang 10 na araw?</p>
                                        <p v-if="language == 'Hiligaynon'">4. Nakaupod mo bala ang tawo nga gina suspetsahan o may kompirmado nga kaso sg CoViD-19 sg nagligad nga 10 ka adlaw?</p>
                                        <p v-if="language == 'Bisaya'">4. Nakahimamat  ba ka sa usa ka gidudahan or kumpirmadong adunay Covid 19 sa mga nilabay nga 10 ka adlaw?</p>
                                        <div class="custom-control custom-radio custom-control-inline ml-4">
                                            <input type="radio" id="yes4" name="four_question" class="custom-control-input" value="Yes" v-model="form.four_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="yes4" >Yes</label>
                                            <label v-if="language=='Tagalog' || language=='Bisaya'" class="custom-control-label" for="yes4" >Oo</label>
                                            <label v-if="language=='Hiligaynon'" class="custom-control-label" for="yes4" >Huo</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="no4" name="four_question" class="custom-control-input" value="No" v-model="form.four_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="no4">No</label>
                                            <label v-if="language == 'Tagalog' || language == 'Hiligaynon'" class="custom-control-label" for="no4">Hindi</label>
                                            <label v-if="language == 'Bisaya'" class="custom-control-label" for="no4">Dili</label>
                                        </div>
                                        <br>    
                                        <span class="text-danger" v-if="errors.four_question">{{ errors.four_question[0] }}</span>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p v-if="language == 'English'">5. Have you been sick or did you experience any of the following in the past 10 days: fever, dry cough, sore throat, nasal congestion, aches and pains, headache, diarrhea, loss of taste or smell, shortness of breath?</p>
                                        <p v-if="language == 'Tagalog'">5. Nagkaroon ka ba ng sakit o alinman sa mga sumusunod sa nagdaang 10 na araw: lagnat, tuyong ubo, pananakit o pagkati ng lalamunan, baradong ilong, sakit ng katawan, sakit ng ulo, pagtatae, pagkawala ng pang-lasa o pang-amoy, at hirap sa paghinga?</p>
                                        <p v-if="language == 'Hiligaynon'">5. Nagmasakit ka bala o naka eksperiensya sg bisan isa sa mga masunod, sa nagligad nga 10 ka adlaw: hilanat, pag-ubo, naga-askad nga tutunlan, barado nga ilong, pagpalanakit sg kalawasan, sakit sg ulo, ginalupot, nadulaan sg panabor o pagpanimaho, nabudlayan sg pagginhawa?</p>
                                        <p v-if="language == 'Bisaya'">5. Nagsakit ba ka o nay gibati sama sa mga sumusunod sa nilabay nga 10 ka adlaw:  kalintura/hilanat, pag-ubo, sakit sa lawas, sakit sa ulo, kalibanga, pagkawala sa panimhot o panglasa,  paglisud sa pag ginhawa?</p>
                                        <div class="custom-control custom-radio custom-control-inline ml-4">
                                            <input type="radio" id="yes5" name="five_question" class="custom-control-input" value="Yes" v-model="form.five_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="yes5" >Yes</label>
                                            <label v-if="language=='Tagalog' || language=='Bisaya'" class="custom-control-label" for="yes5" >Oo</label>
                                            <label v-if="language=='Hiligaynon'" class="custom-control-label" for="yes5" >Huo</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="no5" name="five_question" class="custom-control-input" value="No" v-model="form.five_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="no5">No</label>
                                            <label v-if="language == 'Tagalog' || language == 'Hiligaynon'" class="custom-control-label" for="no5">Hindi</label>
                                            <label v-if="language == 'Bisaya'" class="custom-control-label" for="no5">Dili</label>
                                        </div>
                                        <br>
                                        <span class="text-danger" v-if="errors.five_question">{{ errors.five_question[0] }}</span>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p v-if="language == 'English'">6. Did you have close physical contact with anyone who have fever, dry cough, sore throat, nasal congestion, aches and pains, headache, diarrhea, loss of taste or smell, and shortness of breath in the last 10 days?</p>
                                        <p v-if="language == 'Tagalog'">6. Mayroon ka bang nakalapit ng pisikal at nakasama na taong may lagnat, tuyong ubo, pananakit o pagkati ng lalamunan, baradong ilong, pananakit ng katawan, sakit ng ulo, pagtatae, pagkawala ng pang-lasa o pang-amoy, at hirap sa paghinga sa huling 10 na araw?</p>
                                        <p v-if="language == 'Hiligaynon'">6. Nakapalapit ka bala sa tawo nga may hilanat, naga ubo, may pagpalanakit sang kalawasan, naga-askad nga tutunlan, barado nga ilong, sakit sang ulo, ginalupot, nadulaan sg panabor o pagpanimaho, kag nabudlayan sg pagginhawa sa sulod sang 10 ka adlaw?</p>
                                        <p v-if="language == 'Bisaya'">6. Nakahimamat ba kag duol sa usa ka tao nga adunay kalintura/hilanat, ubo, sakit sa kalawasan, sakit sa ulo, kalibanga, nawad-an ug panglasa o panimhot ug paglisud sa pag ginhawa sulod sa nilabay nga 10 ka adlaw?</p>
                                        <div class="custom-control custom-radio custom-control-inline ml-4">
                                            <input type="radio" id="yes6" name="six_question" class="custom-control-input" value="Yes" v-model="form.six_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="yes6" >Yes</label>
                                            <label v-if="language=='Tagalog' || language=='Bisaya'" class="custom-control-label" for="yes6" >Oo</label>
                                            <label v-if="language=='Hiligaynon'" class="custom-control-label" for="yes6" >Huo</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="no6" name="six_question" class="custom-control-input" value="No" v-model="form.six_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="no6">No</label>
                                            <label v-if="language == 'Tagalog' || language == 'Hiligaynon'" class="custom-control-label" for="no6">Hindi</label>
                                            <label v-if="language == 'Bisaya'" class="custom-control-label" for="no6">Dili</label>
                                        </div>

                                        <!-- <div class="col-md-4 mt-3">
                                            <div class="form-group">
                                                <p>If yes, where?</p> 
                                                <input type="text" class="form-control" v-model="form.six_yes_desc"   @input="form.six_yes_desc=$event.target.value.toUpperCase()">
                                            </div>
                                        </div> -->

                                        <br>
                                        <span class="text-danger" v-if="errors.six_question">{{ errors.six_question[0] }}</span>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p v-if="language == 'English'">7. Were there any confirmed active COVID 19 cases within your block?</p>
                                        <p v-if="language == 'Tagalog'">7. Mayroon bang nakumpirmang aktibong kaso ng COVID 19 sa inyong lugar?</p>
                                        <p v-if="language == 'Hiligaynon'">7. May yara bala  kumpirmado nga aktibo nga kaso sg CoViD 19 malapit sa inyo?</p>
                                        <p v-if="language == 'Bisaya'">7. Aduna bay kumpirmadong Covid 19 na kaso sa inyo lugar?</p>
                                        <div class="custom-control custom-radio custom-control-inline ml-4">
                                            <input type="radio" id="yes7" name="seven_question" class="custom-control-input" value="Yes" v-model="form.seven_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="yes7" >Yes</label>
                                            <label v-if="language=='Tagalog' || language=='Bisaya'" class="custom-control-label" for="yes7" >Oo</label>
                                            <label v-if="language=='Hiligaynon'" class="custom-control-label" for="yes7" >Huo</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="no7" name="seven_question" class="custom-control-input" value="No" v-model="form.seven_question"  >
                                            <label v-if="language == 'English'" class="custom-control-label" for="no7">No</label>
                                            <label v-if="language == 'Tagalog' || language == 'Hiligaynon'" class="custom-control-label" for="no7">Hindi</label>
                                            <label v-if="language == 'Bisaya'" class="custom-control-label" for="no7">Dili</label>
                                        </div>
                                        <br>
                                        <span class="text-danger" v-if="errors.seven_question">{{ errors.seven_question[0] }}</span>
                                        
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p v-if="language == 'English'">8. Have you travelled to other places aside from your home or workplace? </p>
                                        <p v-if="language == 'Tagalog'">8. Ikaw ba ay nagpunta sa ibang lugar bukod sa iyong bahay at lugar ng pinagtatrabahuhan? </p>
                                        <p v-if="language == 'Hiligaynon'">8. Nakalagaw ka bala sa iban nga lugar maluwas sa inyo balay ukon sa imo gina ubrahan?</p>
                                        <p v-if="language == 'Bisaya'">8. Naka bisita ba ka sa laing lugar gawas sa inyong balay o sa trabahuan?</p>
                                        <div class="custom-control custom-radio custom-control-inline ml-4">
                                            <input type="radio" id="yes8" name="eight_question" class="custom-control-input" value="Yes" v-model="form.eight_question"  >
                                            <label v-if="language=='English'" class="custom-control-label" for="yes8" >Yes</label>
                                            <label v-if="language=='Tagalog' || language=='Bisaya'" class="custom-control-label" for="yes8" >Oo</label>
                                            <label v-if="language=='Hiligaynon'" class="custom-control-label" for="yes8" >Huo</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="no8" name="eight_question" class="custom-control-input" value="No" v-model="form.eight_question"  >
                                            <label v-if="language == 'English'" class="custom-control-label" for="no8">No</label>
                                            <label v-if="language == 'Tagalog' || language == 'Hiligaynon'" class="custom-control-label" for="no8">Hindi</label>
                                            <label v-if="language == 'Bisaya'" class="custom-control-label" for="no8">Dili</label>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <div class="form-group">
                                                <p v-if="language == 'English'">If yes, indicate where</p> 
                                                <p v-if="language == 'Tagalog'">Kung Oo, saan</p> 
                                                <p v-if="language == 'Hiligaynon'">Kun Huo, diin ini</p> 
                                                <p v-if="language == 'Bisaya'">Kun Oo, diin ini</p> 
                                                <input type="text" class="form-control" v-model="form.eight_yes_desc"   @input="form.eight_yes_desc=$event.target.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <br>
                                        <span class="text-danger" v-if="errors.eight_yes_desc">{{ errors.eight_yes_desc[0] }}</span>
                                        <span class="text-danger" v-if="errors.eight_question">{{ errors.eight_question[0] }}</span>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p v-if="language == 'English'">9. Did you undergo RT PCR test within 2 weeks?</p>
                                        <p v-if="language == 'Tagalog'">9. Sumailalim ka ba sa RT PCR test sa loob ng 2 linggo?</p>
                                        <p v-if="language == 'Hiligaynon'">9. Sg san-o ka nagpa RT-PCR Test? Kag ano ang resulta? (n/a) (petsa) (positibo, negatibo, ginpa liwat)</p>
                                        <p v-if="language == 'Bisaya'">9. Kanus-a ka ulahing nagpa RT PCR Test?  Og unsa ang resulta?</p>
                                        <div class="custom-control custom-radio custom-control-inline  ml-4">
                                            <input type="radio" id="yes9" name="nineth_question" class="custom-control-input" value="Add Test Results" v-model="form.nineth_question"  >
                                            <label v-if="language == 'English'" class="custom-control-label" for="yes9">If Yes, what is the result?</label>
                                            <label v-if="language == 'Tagalog'" class="custom-control-label" for="yes9">Kung Oo</label>
                                            <label v-if="language == 'Hiligaynon'" class="custom-control-label" for="yes9">Kun Huo</label>
                                            <label v-if="language == 'Bisaya'" class="custom-control-label" for="yes9">Kun Oo</label>
                                            
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="na9" name="nineth_question" class="custom-control-input" value="No" v-model="form.nineth_question"  >
                                            <label v-if="language == 'English'" class="custom-control-label" for="na9" >No</label>
                                            <label v-if="language == 'Tagalog' || language == 'Hiligaynon'" class="custom-control-label" for="na9" >Hindi</label>
                                            <label v-if="language == 'Bisaya'" class="custom-control-label" for="na9" >Dili</label>
                                        </div>
                                        
                                        <div class="col-md-4 mt-3" v-if="form.nineth_question == 'Add Test Results'">
                                            <div class="form-group">
                                                <p>Testing Date</p> 
                                                <input type="date" class="form-control" v-model="form.nineth_question_testing_date"  >
                                            </div>
                                            <div class="form-group">
                                                <p>Test Results</p> 
                                                <select class="form-control" v-model="form.nineth_question_testing_results">
                                                    <option value="Negative">Negative</option>
                                                    <option value="Positive">Positive</option>
                                                    <option value="Repeat">Repeat</option>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <span class="text-danger" v-if="errors.nineth_question">{{ errors.nineth_question[0] }}</span>
                                        
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p v-if="language == 'English'">10. Did you answer this online health declaration form with full disclosure and honesty? </p>
                                        <p v-if="language == 'Tagalog'">10. Nasagot mo ba ang Health Declaration Form na may buong katotohanan at katapatan? </p>
                                        <p v-if="language == 'Hiligaynon'">10. Ginsabat mo bala ining online health declaration form nga may kamatuoran kag wala sg may pagpangduha-duha? </p>
                                        <p v-if="language == 'Bisaya'">10. Imo ba ning gitubag ang Health Declaration Form nga tinud-anay og walay pagpamakak?</p>
                                        <div class="custom-control custom-radio custom-control-inline ml-4">
                                            <input type="radio" id="yes10" name="tenth_question" class="custom-control-input" value="Yes" v-model="form.tenth_question"  >
                                            <label v-if="language == 'English'" class="custom-control-label" for="yes10" >Yes</label>
                                            <label v-if="language == 'Tagalog' || language == 'Bisaya'" class="custom-control-label" for="yes10" >Oo</label>
                                            <label v-if="language == 'Hiligaynon'" class="custom-control-label" for="yes10" >Huo</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="no10" name="tenth_question" class="custom-control-input" value="No" v-model="form.tenth_question"  >
                                            <label v-if="language == 'English'" class="custom-control-label" for="no10">No</label>
                                            <label v-if="language == 'Tagalog' || language == 'Hiligaynon'" class="custom-control-label" for="no10">Hindi</label>
                                            <label v-if="language == 'Bisaya'" class="custom-control-label" for="no10">Dili</label>
                                        </div>
                                        <br>
                                        <span class="text-danger" v-if="errors.tenth_question">{{ errors.tenth_question[0] }}</span>
                                        
                                    </div>
                                </div>    
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary btn-md" @click="saveQrHDF" :disabled="saveDisable">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</template>

<script>
    export default {
        data() {
            return {
                employee: '',
                form : [],
                errors : [],
                language : 'English',

                qr_hdf : '',

                saveDisable : false,

                newQR : true,
            }
        },
        created () {
            this.getDashboardData();
        },
        methods: {
            imageLoadError(event) { 
                event.target.src = "/img/imagenotavailable.PNG"; 
            },
            clearForm(){
                this.newQR = false;
                this.form.one_question = "";
                this.form.two_question = "";
                this.form.three_question = "";
                this.form.four_question = "";
                this.form.five_question = "";
                this.form.six_question = "";
                this.form.seven_question = "";
                this.form.seven_yes_desc = "";
                this.form.eight_question = "";
                this.form = [];
            },
            saveQrHDF(){
                let v = this;
                v.saveDisable = true;
                Swal.fire({
                    title: 'Are you sure you want to save this hdf?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('one_question', v.form.one_question ? v.form.one_question : "");
                        formData.append('two_question', v.form.two_question ? v.form.two_question : "");
                        formData.append('three_question', v.form.three_question ? v.form.three_question : "");
                        formData.append('four_question', v.form.four_question ? v.form.four_question : "");
                        formData.append('five_question', v.form.five_question ? v.form.five_question : "");
                        formData.append('six_question', v.form.six_question ? v.form.six_question : "");
                        formData.append('seven_question', v.form.seven_question ? v.form.seven_question : "");
                        formData.append('eight_question', v.form.eight_question ? v.form.eight_question : "");
                        formData.append('eight_yes_desc', v.form.eight_yes_desc ? v.form.eight_yes_desc : "");
                        if(v.form.nineth_question == 'Add Test Results'){
                            v.form.nineth_question = v.form.nineth_question_testing_date + ' - ' + v.form.nineth_question_testing_results;
                            formData.append('nineth_question', v.form.nineth_question ? v.form.nineth_question : "");
                        }else{
                            formData.append('nineth_question', v.form.nineth_question ? v.form.nineth_question : "");
                        }
                        formData.append('tenth_question', v.form.tenth_question ? v.form.tenth_question : "");
                        axios.post(`/save-qr-hdf`, formData)
                        .then(response => {
                            if(response.data.status == 'saved'){
                                 Swal.fire({
                                    title: 'New QR Generated.',
                                    text: 'Successfully saved. Save your QR to scan in our site location.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                    // timer : 5000
                                }).then(okay => {
                                    if (okay) {
                                        location.reload();
                                    }
                                });
                                
                            }
                        })
                        .catch(error => {
                            v.errors = error.response.data.errors;
                            v.saveDisable = false;
                        })
                    }else{
                        v.saveDisable = false;
                    }
                })
            },
            getDashboardData() {
                let v = this;
                axios.get('/home-data')
                .then(response => { 
                    if(response.data){
                        v.qr_hdf = response.data;
                        v.form = response.data;
                    }else{
                        v.newQR = false;
                    }
                   
                })
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>