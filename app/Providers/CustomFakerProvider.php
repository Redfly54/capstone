<?php
namespace App\Providers;

use Faker\Provider\Base;

class CustomFakerProvider extends Base
{
    public function kelurahan()
    {
        return static::randomElement(['Cengkareng Barat', 'Cengkareng Timur', 'Duri Kosambi', 'Kalideres', 'Kembangan Selatan', 'Kembangan Utara', 'Kedoya Utara', 'Palmerah', 'Slipi', 'Tanjung Duren Utara']
);
    }

    public function kecamatan()
    {
        return static::randomElement(['Cengkareng', 'Grogol Petamburan', 'Kalideres', 'Kebon Jeruk', 'Kembangan', 'Palmerah', 'Tambora', 'Taman Sari', 'Cilincing', 'Penjaringan']
);
    }

    public function alamat()
    {
        return static::randomElement([
            'Jl. Tanjung Duren Raya No.45',
            'Jl. Kembangan Raya No.12',
            'Jl. Duri Kosambi No.7A',
            'Jl. Peta Selatan No.89',
            'Jl. Palmerah Utara No.25',
            'Jl. Kebon Jeruk Raya No.17',
            'Jl. Tomang Raya No.9',
            'Jl. Taman Sari Raya No.3',
            'Jl. Anggrek Neli Murni No.23',
            'Jl. Tambora I No.14'
        ]);
    }

    public function tipeHewan()
    {
        return static::randomElement(['dog', 'cat', 'rabbit']);
    }

    public function jenisHewan(string $tipeHewan)
    {
        $options = [
            'dog' => ['Bulldog', 'kampung', 'Retriever', 'beagle', 'German Shepherd', 'another'],
            'cat' => ['Persia', 'angora', 'kampung', 'siam', 'Bengal', 'another'],
            'rabbit' => ['lop', 'dwar', 'anggora', 'himalayan', 'havana'],
        ];

        return static::randomElement($options[$tipeHewan] ?? []);
    }

    public function genderHewan()
    {
        return static::randomElement(['jantan', 'betina']);
    }

    public function kelompokUsia(string $tipeHewan)
    {
        $options = [
            'dog' => ['puppy', 'adult', 'senior'],
            'cat' => ['kitten', 'adult', 'senior'],
            'rabbit' => ['muda', 'adult', 'senior'],
        ];

        return static::randomElement($options[$tipeHewan] ?? []);
    }

    public function jumlahWarna()
    {
        return static::randomElement([1, 2, 3, 4]);
    }
}