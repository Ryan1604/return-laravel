@extends('admin.layouts.master')
@section('title', 'Dashboard')

@section('content')
   <!-- Main Content -->
   <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Dashboard</h1>
      </div>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-12 col-sm-12">
          <div class="card">
            <div class="card-header">
              <h4>Dashboard</h4>
            </div>
            <div class="card-body">
                <img alt="image" src="{{ asset('img/Dashboard.jpeg') }}" width="1100" >
                <h1 class="text-center mt-5">PT. DHARMA ELECTRINDO MANUFACTURING</h1>
                <p>
                    PT. Dharma Electrindo Manufacturing ( PT. DEM ) yang berdiri pada bulan Agustus 2002 memfokuskan bisnisnya dibidang Wiring Harness dan komponen electric lainnya untuk memasok kebutuhan produsen otomotif terkemuka dan senantiasa mengembangkan bisnisnya sesuai prinsip Manajemen Mutu ISO TS 16949 dan Manajemen Lingkungan ISO 14001.
                </p>

                <p>
                    Untuk memberikan pelayanan terbaiknya, PT. Dharma Electrindo Manufacturing melengkapi fasilitas produksi dengan teknologi terkini, antara lain : mesin Auto-Cutting & Crimping, Auto-Cutting & Middle Stripping, Conveyor Assembling, dan Electrical Tester. Sebagai komitmen perusahaan untuk senantiasa menjaga kualitas Sumber Daya Manusia, perusahaan memiliki DOJO dengan program yang intensive dan terprogram dengan prasarana yang lengkap.
                </p>
                    
                <p> 
                    facilities terkait standar kualitas, juga tersedia seperti : Pull Tester, Air Leak Tester, Harness Flec Resistance Test & Harness Torsibility, Digital Microscope, Edges & Burrs Tester, Heating Tester, dan Fuse Tester.
                </p>
                <p>Produk-produk PT. Dharma Electrindo Manufacturing antara lain meliputi :</p>
                <ul>
                    <li>Sepeda Motor : Main Wiring Harness, Wiring Harness Speedometer, Speed Sensor Cable, dan Battery Harness</li>
                    <li>Mobil : Main Harness, Instrument Panel Harness, Door Harness, Battery Harness</li>
                    <li>Accessories & Others : Parking Sensor, Security Alarm</li>
                </ul>
                <p>Customer kami antara lain adalah :</p>
                <ul>
                    <li>PT. Astra Honda Motor</li>
                    <li>PT. Indonesia Nippon Seiki (NS)</li>
                    <li>PT. Hyundai Motor Indonesia</li>
                    <li>PT. Toyota Astra Motor</li>
                    <li>PT. Toyo Denso Indonesia</li>
                    <li>PT. Astra International – Daihatsu Sales Operation</li>
                </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection


