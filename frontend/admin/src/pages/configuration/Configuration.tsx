import { Card, CardContent } from '@mui/material';
import {
  ArrayInput, FileInput, ImageField,
  required,
  SimpleForm,
  SimpleFormIterator,
  TextInput,
  Title,
  useDataProvider
} from "react-admin";
import {useEffect, useState} from "react";

interface Property {
  key: string,
  value: string,
}

interface Config {
  userName: string,
  roles: string[],
  about: string,
  githubLink: string,
  linkedinLink: string,
  siteUrl: string,
  phoneNumber: string,
  emailAddress: string,
  userPhoto: object,
}

const recordDefaults: Config = {
  userName: '',
  roles: [],
  about: '',
  githubLink: '',
  linkedinLink: '',
  siteUrl: '',
  emailAddress: '',
  phoneNumber: '',
  userPhoto: {},
}

const Configuration = () => {
  const dataProvider = useDataProvider();
  const [record, setRecord] = useState<Config>(recordDefaults);

  const loadData = () => {
    dataProvider.get("properties").then((properties: Property[]) => {
      properties.forEach( (property: Property) => {
        const key: string = property.key;
        const value: string = property.value;

        if (record.hasOwnProperty(key)) {
          if (key === 'roles') {
            let json = [];
            try {
              json = JSON.parse(value);
            } catch (e) {}

            record[key] = json;
          }
          else if (key === "userPhoto") {
            if (!isNaN(parseInt(value))) {
              dataProvider.getOne("storage", {id: value}).then(({data}) => {
                record[key] = data;
              });
            }
          }
          else {
            record[key] = value;
          }
        }
      });

      setRecord(record);
    });


    // dataProvider.
    // get data
  };
  // userName: string
  // roles: json
  // about: string
  // githubLink: string
  // linkedinLink: string
  // siteUrl
  // emailAddress
  // userPhotoId

  useEffect(() => {
    loadData();
  }, []);

  const saveConfiguration = async (data: any) => {
    let dataToSend: Property[] = [];

    // transform data
    data.roles = JSON.stringify(data.roles);

    if (data.hasOwnProperty('userPhoto')) {
      // is new File
      if (data.userPhoto.hasOwnProperty('rawFile'))
      {
        let formData = new FormData();
        let rawFile = data.userPhoto.rawFile;
        formData.append('file', rawFile, rawFile.name);

        const result  = await dataProvider.sendForm("storage", formData);

        data.userPhoto = result.data.id.toString();
      }
      else {
        data.userPhoto = data.userPhoto.id.toString();
      }
    }

    for (let key in data) {
      dataToSend.push({
        key: key,
        value: data[key],
      })
    }

    dataProvider.patch("properties", {properties: dataToSend});
  };

  return (
    <Card>
      <Title title='Configuration' />
      <CardContent>
        <SimpleForm onSubmit={saveConfiguration} record={record}>
          <TextInput autoFocus source="userName" fullWidth validate={required()}/>
          <FileInput source="userPhoto" validate={required()} accept=".jpg, .jpeg">
            <ImageField source="src" title="title" />
          </FileInput>
          <ArrayInput source="roles" validate={required()}>
            <SimpleFormIterator>
              <TextInput source=''/>
            </SimpleFormIterator>
          </ArrayInput>
          <TextInput source="about" fullWidth validate={required()}/>
          <TextInput source="githubLink" fullWidth validate={required()}/>
          <TextInput source="linkedinLink" fullWidth validate={required()}/>
          <TextInput source="siteUrl" fullWidth validate={required()}/>
          <TextInput source="emailAddress" fullWidth validate={required()}/>
          <TextInput source="phoneNumber" fullWidth validate={required()}/>
        </SimpleForm>
      </CardContent>
    </Card>
  );
};

export default Configuration;
