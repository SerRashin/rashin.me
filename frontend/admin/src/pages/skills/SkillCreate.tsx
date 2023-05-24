import {
  AutocompleteInput,
  Create,
  FileInput,
  ImageField,
  ReferenceInput,
  required,
  SimpleForm,
  TextInput
} from 'react-admin';

export const SkillCreate = () => (
  <Create>
    <SimpleForm>
      <TextInput autoFocus source="name" fullWidth validate={required()}/>
      <ReferenceInput source="sectionId" reference="sections" >
        <AutocompleteInput label="Section" optionText="name" fullWidth/>
      </ReferenceInput>
      <FileInput source="image" validate={required()}>
        <ImageField source="src" title="title" />
      </FileInput>
      <TextInput source="description" multiline fullWidth minRows={5}/>
    </SimpleForm>
  </Create>
);
