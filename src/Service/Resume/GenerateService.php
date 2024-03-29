<?php

declare(strict_types=1);

namespace RashinMe\Service\Resume;

use Dompdf\Dompdf;
use Dompdf\Options;
use RashinMe\Service\Education\EducationService;
use RashinMe\Service\Education\Filter\EducationFilter;
use RashinMe\Service\Education\Filter\EducationSort;
use RashinMe\Service\Job\Filter\JobFilter;
use RashinMe\Service\Job\Filter\JobSort;
use RashinMe\Service\Job\JobService;
use RashinMe\Service\Project\Filter\ProjectFilter;
use RashinMe\Service\Project\ProjectService;
use RashinMe\Service\Property\Filter\PropertyFilter;
use RashinMe\Service\Property\PropertyService;
use RashinMe\Service\Skill\Filter\SectionFilter;
use RashinMe\Service\Skill\SectionService;
use RashinMe\Service\Storage\StorageService;
use Twig\Environment;

class GenerateService
{
    public function __construct(
        private readonly Environment $twig,
        private readonly SectionService $sectionService,
        private readonly EducationService $educationService,
        private readonly ProjectService $projectService,
        private readonly JobService $jobService,
        private readonly PropertyService $propertyService,
        private readonly StorageService $storageService,
    ) {
    }

    public function execute()
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('enable_remote', true);

        $dompdf = new Dompdf($pdfOptions);
        $icons = $this->getIcons();

        $sections = $this->sectionService->getSections(new SectionFilter(100, 0));
        $educations = $this->educationService->getEducations(
            new EducationFilter(100, 0),
            new EducationSort('from', 'asc'),
        );
        $jobs = $this->jobService->getJobs(
            new JobFilter(100, 0),
            new JobSort('from', 'asc'),
        );
        $projects = $this->projectService->getProjects(
            new ProjectFilter(100, 0),
        );

        $properties = $this->propertyService->getProperties(new PropertyFilter([]));

        $links = [];
        $photo = null;
        $about = null;
        foreach ($properties as $property) {
            $key = $property->getKey();
            $value = $property->getValue();

            if (in_array($key, [
                'githubLink',
                'linkedinLink',
                'siteUrl',
                'emailAddress',
                'phoneNumber',
            ])) {
                $links[$key] = $value;
            }
            elseif ($key === 'userPhoto') {
                $file = $this->storageService->getFileById((int)$value);

                if ($file !== null) {
                    $base64 = $this->getBase64Image(
                        $_SERVER["DOCUMENT_ROOT"] . $file->getPath() . $file->getName(),
                        $file->getMimeType(),
                    );
                    if ($base64 != null) {
                        $photo = $base64;
                    }
                }
            }
            elseif ($key === 'about') {
                $about = $value;
            }
        }


        $html = $this->twig->render('Resume/resume.html.twig', [
            'title' => "Sergey Rashin",
            'icons' => $icons,
            'sections' => $sections,
            'educations' => $educations,
            'jobs' => $jobs,
            'projects' => $projects,
            'links' => $links,
            'userPhoto' => $photo,
            'about' => $about,
        ]);

        $dompdf->loadHtml($html, 'UTF-8');

        $dompdf->setPaper('A4');

        $dompdf->render();

        $dompdf->stream("SergeyRashin.pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * Dompdf not supports svg images or font icons
     *
     * @return string[]
     */
    public function getIcons(): array
    {
        // @codingStandardsIgnoreStart
        return [
            'linkedin' => base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="#1672d0" d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>'),
            'github' => base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path fill="#1672d0" d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"/></svg>'),
            'phone' => base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#1672d0" d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/></svg>'),
            'email' => base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#1672d0" d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>'),
            'site' => base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#1672d0" d="M352 256c0 22.2-1.2 43.6-3.3 64H163.3c-2.2-20.4-3.3-41.8-3.3-64s1.2-43.6 3.3-64H348.7c2.2 20.4 3.3 41.8 3.3 64zm28.8-64H503.9c5.3 20.5 8.1 41.9 8.1 64s-2.8 43.5-8.1 64H380.8c2.1-20.6 3.2-42 3.2-64s-1.1-43.4-3.2-64zm112.6-32H376.7c-10-63.9-29.8-117.4-55.3-151.6c78.3 20.7 142 77.5 171.9 151.6zm-149.1 0H167.7c6.1-36.4 15.5-68.6 27-94.7c10.5-23.6 22.2-40.7 33.5-51.5C239.4 3.2 248.7 0 256 0s16.6 3.2 27.8 13.8c11.3 10.8 23 27.9 33.5 51.5c11.6 26 20.9 58.2 27 94.7zm-209 0H18.6C48.6 85.9 112.2 29.1 190.6 8.4C165.1 42.6 145.3 96.1 135.3 160zM8.1 192H131.2c-2.1 20.6-3.2 42-3.2 64s1.1 43.4 3.2 64H8.1C2.8 299.5 0 278.1 0 256s2.8-43.5 8.1-64zM194.7 446.6c-11.6-26-20.9-58.2-27-94.6H344.3c-6.1 36.4-15.5 68.6-27 94.6c-10.5 23.6-22.2 40.7-33.5 51.5C272.6 508.8 263.3 512 256 512s-16.6-3.2-27.8-13.8c-11.3-10.8-23-27.9-33.5-51.5zM135.3 352c10 63.9 29.8 117.4 55.3 151.6C112.2 482.9 48.6 426.1 18.6 352H135.3zm358.1 0c-30 74.1-93.6 130.9-171.9 151.6c25.5-34.2 45.2-87.7 55.3-151.6H493.4z"/></svg>'),
        ];
        // @codingStandardsIgnoreEnd
    }

    public function getBase64Image(string $path, string $ext): string|null
    {
        $data = file_get_contents($path);
        if (!$data)
            return null;

        return  'data:' . $ext . ';base64,' . base64_encode($data);
    }
}
