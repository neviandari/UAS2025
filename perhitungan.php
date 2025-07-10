<?php
require_once('includes/init.php');

$user_role = get_role();
if ($user_role == 'admin' || $user_role == 'user') {

	$page = "Perhitungan";
	require_once('template/header.php');

	mysqli_query($koneksi, "TRUNCATE TABLE hasil;");

	$kriteria = array();
	$q1 = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
	while ($krit = mysqli_fetch_array($q1)) {
		$kriteria[$krit['id_kriteria']]['id_kriteria'] = $krit['id_kriteria'];
		$kriteria[$krit['id_kriteria']]['kode_kriteria'] = $krit['kode_kriteria'];
		$kriteria[$krit['id_kriteria']]['kriteria'] = $krit['kriteria'];
		$kriteria[$krit['id_kriteria']]['type'] = $krit['type'];
		$kriteria[$krit['id_kriteria']]['bobot'] = $krit['bobot'];
		$kriteria[$krit['id_kriteria']]['ada_pilihan'] = $krit['ada_pilihan'];
	}

	$alternatif = array();
	$q2 = mysqli_query($koneksi, "SELECT * FROM alternatif");
	while ($alt = mysqli_fetch_array($q2)) {
		$alternatif[$alt['id_alternatif']]['id_alternatif'] = $alt['id_alternatif'];
		$alternatif[$alt['id_alternatif']]['alternatif'] = $alt['alternatif'];
	}
?>

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-calculator"></i> Data Perhitungan</h1>
	</div>

	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold"><i class="fa fa-table"></i> Bobot Preferensi (W)</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="bg-primary text-white">
						<tr align="center">
							<?php foreach ($kriteria as $key) : ?>
								<th><?= $key['kode_kriteria'] ?> (<?= $key['type'] ?>)</th>
							<?php endforeach ?>
						</tr>
					</thead>
					<tbody>
						<tr align="center">
							<?php foreach ($kriteria as $key) : ?>
								<td><?= $key['bobot']; ?></td>
							<?php endforeach ?>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold"><i class="fa fa-table"></i> Matrix Keputusan (X)</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="bg-primary text-white text-center">
						<tr>
							<th width="5%" rowspan="2">No</th>
							<th>Nama Alternatif</th>
							<?php foreach ($kriteria as $key) : ?>
								<th><?= $key['kode_kriteria'] ?></th>
							<?php endforeach ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($alternatif as $keys) : ?>
							<tr align="center">
								<td><?= $no++; ?></td>
								<td align="left"><?= $keys['alternatif'] ?></td>
								<?php foreach ($kriteria as $key) : ?>
									<td><?= get_matriks_keputusan($keys['id_alternatif'], $key['id_kriteria']) ?? 'n/a'; ?></td>
								<?php endforeach ?>
							</tr>
						<?php endforeach ?>
						<tr class="text-center bg-dark text-white fw-bold">
							<td colspan="2"><b>Max</b></td>
							<?php foreach ($kriteria as $k) : ?>
								<td><?= get_max_min($k['id_kriteria'])['max'] ?></td>
							<?php endforeach ?>
						</tr>
						<tr class="text-center bg-dark text-white fw-bold">
							<td colspan="2"><b>Min</b></td>
							<?php foreach ($kriteria as $k) : ?>
								<td><?= get_max_min($k['id_kriteria'])['min'] ?></td>
							<?php endforeach ?>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold"><i class="fa fa-table"></i> Matriks Ternormalisasi (R)</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="bg-primary text-white">
						<tr align="center">
							<th width="5%" rowspan="2">No</th>
							<th rowspan="2">Nama Alternatif</th>
							<?php $no = 1; ?>
							<?php foreach ($kriteria as $key) : ?>
								<th><?= "R" . $no++ ?></th>
							<?php endforeach ?>
						</tr>
						<tr>
							<?php foreach ($kriteria as $key) : ?>
								<th class="text-center"><i><small><?= $key['type'] ?></small></i></th>
							<?php endforeach ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($alternatif as $keys) : ?>
							<tr>
								<td><?= $no++; ?></td>
								<td align="left"><?= $keys['alternatif'] ?></td>
								<?php foreach ($kriteria as $key) : ?>
									<td>
										<?php
										$matrik_keputusan = get_matriks_keputusan($keys['id_alternatif'], $key['id_kriteria']);
										if ($matrik_keputusan === null) {
											echo "n/a"; // Jika tidak ada nilai, tampilkan N/A
											continue; // Lewati iterasi ini
										} else {
											$max = get_max_min($key['id_kriteria'])['max'];
											$min = get_max_min($key['id_kriteria'])['min'];

											if ($key['type'] == "Benefit") {
												echo $matrik_keputusan . "/" . $max . " = " . custom_number_format($matrik_keputusan / $max);
											} else {
												echo $min . "/" . $matrik_keputusan . " = " . custom_number_format($min / $matrik_keputusan);
											}
										}

										?>
									</td>
								<?php endforeach; ?>

							</tr>
						<?php endforeach ?>
						<tr class="text-center bg-dark text-white fw-bold">
							<td colspan="2">Bobot</td>
							<?php foreach ($kriteria as $key) : ?>
								<td><?= $key['bobot']; ?></td>
							<?php endforeach ?>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold"><i class="fa fa-table"></i> Menghitung Nilai Preferensi (V)</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="bg-primary text-white">
						<tr align="center">
							<th width="5%" rowspan="2">No</th>
							<th>Nama Alternatif</th>
							<?php $no = 1; ?>
							<?php foreach ($kriteria as $key) : ?>
								<td><?= "V" . $no++; ?></td>
							<?php endforeach ?>
							<th>Nilai</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($alternatif as $keys) : ?>
							<tr>
								<td><?= $no++; ?></td>
								<td align="left"><?= $keys['alternatif'] ?></td>

								<?php
								$nilai_v = 0; // Pastikan $nilai_v diinisialisasi sebelum digunakan
								foreach ($kriteria as $key) :
									$matrik_keputusan = get_matriks_keputusan($keys['id_alternatif'], $key['id_kriteria']);
									if ($matrik_keputusan === null) {
										echo "<td>n/a</td>"; // Jika tidak ada nilai, tampilkan N/A
										continue; // Lewati iterasi ini
									} else {
										$max = get_max_min($key['id_kriteria'])['max'];
										$min = get_max_min($key['id_kriteria'])['min'];

										$nilai = ($key['type'] == "Benefit") ? ($matrik_keputusan / $max) : ($min / $matrik_keputusan);
										echo "<td>" . custom_number_format($nilai * $key['bobot']) . "</td>";

										// Hitung nilai preferensi
										$nilai_v += $nilai * $key['bobot'];
									}
								endforeach ?>

								<td class="bg-dark text-white">
									<?php
									echo custom_number_format($nilai_v);
									// Simpan nilai preferensi ke tabel hasil
									save_hasil($keys['id_alternatif'], $nilai_v); // Simpan nilai preferensi ke database
									?>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold"><i class="fa fa-table"></i> Perankingan</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="bg-primary text-white">
						<tr align="center">
							<th>No</th>
							<th>Nama Alternatif</th>
							<th>Nilai</th>
							<th width="15%">Ranking</th>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$rank = 1;
						// $query = mysqli_query($koneksi, "SELECT * FROM hasil ORDER BY nilai DESC");
						$query = mysqli_query($koneksi, "SELECT * FROM hasil JOIN alternatif ON hasil.id_alternatif=alternatif.id_alternatif ORDER BY hasil.nilai DESC");
						while ($data = mysqli_fetch_array($query)) : ?>
							<tr align="center">
								<td><?= $no++ ?></td>
								<td align="left"><?= $data['alternatif'] ?></td>
								<td><?= custom_number_format($data['nilai']) ?></td>
								<td class="bg-dark text-white"><?= $rank++; ?></td>
							</tr>
						<?php endwhile ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<?php
	require_once('template/footer.php');
} else {
	header('Location: login.php');
}
?>