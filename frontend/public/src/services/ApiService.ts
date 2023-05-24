const apiUrl = `${process.env.API_URL}`;

const endpoints = {
  projects: '/projects',
  skillSections: '/sections',
  skills: '/skills',
  jobs: '/jobs',
  educations: '/educations',
  properties: '/properties',
  storage: '/storage',
};

export interface Image {
  readonly id: number,
  readonly src: string,
}

export interface File {
  readonly id: number,
  readonly name: string,
  readonly path: string,
  readonly mimeType: string,
  readonly size: number,
}

export interface Link {
  readonly id: number,
  readonly title: string,
  readonly url: string,
}

export interface Company {
  readonly name: string,
  readonly url: string,
}

export interface WorkDate {
  readonly month: number,
  readonly year: number,
}

export interface Project {
  readonly id: number,
  readonly name: string,
  readonly image: Image,
  readonly description: string,
  readonly links: Link[],
  readonly tags: string[]
}


export interface Job {
  readonly id: number,
  readonly image: Image,
  readonly name: string,
  readonly type: string,
  readonly description: string,
  readonly company: Company,
  readonly from: WorkDate,
  readonly to: WorkDate|null,
}

export interface Education {
  readonly id: number,
  readonly institution: string,
  readonly faculty: string,
  readonly specialization: string,
  readonly from: WorkDate,
  readonly to: WorkDate|null,
}

export interface Section {
  readonly id: number,
  readonly name: string,
  readonly skills: Skill[],
}

export interface Skill {
  readonly id: number,
  readonly image: Image,
  readonly name: string,
  readonly sectionId: number,
  readonly description: string,
}

export interface Property {
  key: string,
  value: string,
}

export interface PaginatedCollection<T> {
  readonly data: T[],
  readonly pagination: {
    readonly limit: number,
    readonly offset: number,
    readonly total: number
  }
}

class ApiService {
  apiEndpoint: string;
  constructor(apiEndpoint: string) {
    this.apiEndpoint = apiEndpoint;
  }

  async getSections(): Promise<PaginatedCollection<Section>>
  {
    const url = apiUrl + endpoints.skillSections;

    return await fetch(url, {
      method: 'GET',
    })
      .then((res: Response) => res.json())
  }

  async getProjects(limit: number, offset: number): Promise<PaginatedCollection<Project>>
  {
    const url = apiUrl + endpoints.projects + `?limit=${ limit }&offset=${ offset }`;

    return await fetch(url, {
      method: 'GET',
    })
      .then((res: Response) => res.json())
  }

  async getJobs(): Promise<PaginatedCollection<Job>>
  {
    const url = apiUrl + endpoints.jobs + '?sort={"field":"from","order":"desc"}';

    return await fetch(url, {
      method: 'GET',
    })
      .then((res: Response) => res.json())
  }

  async getConfiguration(fields: string[]): Promise<Property[]>
  {
    const filterData = {
      fields: fields,
    };
    const url = apiUrl + endpoints.properties + `?filter=${JSON.stringify(filterData)}`;

    return await fetch(url, {
      method: 'GET',
    })
      .then((res: Response) => res.json())
  }

  async getEducations(): Promise<PaginatedCollection<Education>>
  {
    const url = apiUrl + endpoints.educations + '?sort={"field":"from","order":"asc"}';

    return await fetch(url, {
      method: 'GET',
    })
      .then((res: Response) => res.json())
  }

  async getFile(fileId: number): Promise<File>
  {
    const url = apiUrl + endpoints.storage + '/' + fileId;

    return await fetch(url, {
      method: 'GET',
    })
      .then((res: Response) => res.json())
  }
}

const apiService = new ApiService(apiUrl);

export default apiService;
