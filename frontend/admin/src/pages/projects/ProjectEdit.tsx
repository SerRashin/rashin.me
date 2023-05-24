import {
  ArrayInput,
  Edit,
  FileInput,
  ImageField,
  NumberInput,
  required,
  SimpleForm,
  SimpleFormIterator,
  TextInput, useRecordContext
} from 'react-admin';

const ProjectTitle = () => {
  const record = useRecordContext();

  return <span>Edit Project {record ? `"${record.name}"` : ''}</span>;
};

export const ProjectEdit = () => (
    <Edit title={<ProjectTitle />}>
        <SimpleForm>
            <TextInput autoFocus source="name" fullWidth validate={required()}/>
            <FileInput source="image" validate={required()}>
                <ImageField source="src" title="title" />
            </FileInput>
            <TextInput source="description" multiline fullWidth minRows={5}/>
            <ArrayInput source="links">
                <SimpleFormIterator inline>
                    <NumberInput disabled source="id" defaultValue={0} style={{'display':'none'}}/>
                    <TextInput source="title" />
                    <TextInput source="url" />
                </SimpleFormIterator>
            </ArrayInput>
            <ArrayInput source="tags" validate={required()}>
                <SimpleFormIterator>
                    <TextInput source=''/>
                </SimpleFormIterator>
            </ArrayInput>
        </SimpleForm>
    </Edit>
);
